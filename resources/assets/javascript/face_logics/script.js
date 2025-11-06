var labels = [];
let detectedFaces = [];
let sendingData = false;
let users = [];  // This will hold the student data with nomor_registrasi and nama

function updateTable() {
    var container = document.getElementById("studentTableContainer");
    if (!container) {
        console.error("Element with ID 'studentTableContainer' not found.");
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "home/fetch_user", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.status === "success") {
                container.innerHTML = response.html;
            } else {
                console.error("Error:", response.message);
            }
        }
    };

    xhr.send(); // No data to send since we're fetching all users
}

function markAttendance(detectedFaces) {
  document.querySelectorAll("#studentTableContainer tr").forEach((row) => {
    const nomor_registrasi = row.cells[0].innerText.trim();
    if (detectedFaces.includes(nomor_registrasi)) {
      row.cells[5].innerText = "present";
    }
  });
}

function updateOtherElements() {
  const video = document.getElementById("video");
  const videoContainer = document.querySelector(".video-container");
  const startButton = document.getElementById("startButton");
  let webcamStarted = false;
  let modelsLoaded = false;
  console.log("Try to call updateOtherElements"),

  Promise.all([
  console.log("Try to call models"),
  faceapi.nets.ssdMobilenetv1.loadFromUri("<?= base_url('models') ?>"),
  faceapi.nets.faceRecognitionNet.loadFromUri("<?= base_url('models') ?>"),
  faceapi.nets.faceLandmark68Net.loadFromUri("<?= base_url('models') ?>"),
  ])
    .then(() => {
      modelsLoaded = true;
      console.log("models loaded successfully");
    })
    .catch(() => {
      alert("models not loaded, please check your model folder location");
    });
  startButton.addEventListener("click", async () => {
    videoContainer.style.display = "flex";
    if (!webcamStarted && modelsLoaded) {
      startWebcam();
      webcamStarted = true;
    }
  });

  function startWebcam() {
    navigator.mediaDevices
      .getUserMedia({
        video: true,
        audio: false,
      })
      .then((stream) => {
        video.srcObject = stream;
        videoStream = stream;
      })
      .catch((error) => {
        console.error(error);
      });
  }

  async function getLabeledFaceDescriptions() {
    const labeledDescriptors = [];

    for (const label of labels) {
      console.log(labels);
      const descriptions = [];
      // Find the student matching the nomor_registrasi (label)
      const user = users.find(s => s.nomor_registrasi === label);

      if (student) {
        const nama = user.nama; // Get the student's first name
        const nomor_registrasi = user.nomor_registrasi; // Get the registration number
        for (let i = 1; i <= 5; i++) {
          try {
            const img = await faceapi.fetchImage(
              `resources/labels/${label}/${i}.png`
            );
            const detections = await faceapi
              .detectSingleFace(img)
              .withFaceLandmarks()
              .withFaceDescriptor();

            if (detections) {
              descriptions.push(detections.descriptor);
            } else {
              console.log(`No face detected in ${label}/${i}.png`);
            }
          } catch (error) {
            console.error(`Error processing ${label}/${i}.png:`, error);
          }
        }

        if (descriptions.length > 0) {
          labeledDescriptors.push(
            new faceapi.LabeledFaceDescriptors(nama, descriptions) // Use nama here
          );
        }
      }
    }

    return labeledDescriptors;
}

video.addEventListener("play", async () => {
    const labeledFaceDescriptors = await getLabeledFaceDescriptions();
    const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors);

    const canvas = faceapi.createCanvasFromMedia(video);
    videoContainer.appendChild(canvas);

    const displaySize = { width: video.width, height: video.height };
    faceapi.matchDimensions(canvas, displaySize);

    setInterval(async () => {
      const detections = await faceapi
        .detectAllFaces(video)
        .withFaceLandmarks()
        .withFaceDescriptors();

      const resizedDetections = faceapi.resizeResults(detections, displaySize);

      canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);

      const results = resizedDetections.map((d) => {
        return faceMatcher.findBestMatch(d.descriptor);
      });

      // Now map the results to include registration numbers
      detectedFaces = results.map((result) => {
        // We are returning the registration number instead of nama
        const user = users.find(s => s.nama === result.label);
        return user? user.nomor_registrasi : null;
      }).filter(Boolean); // Remove any null values

      console.log(detectedFaces); // Here you'll see the registration numbers
      markAttendance(detectedFaces);

      results.forEach((result, i) => {
        const box = resizedDetections[i].detection.box;
        const drawBox = new faceapi.draw.DrawBox(box, {
          label: result.label, // You can keep nama as label here for visual purposes
        });
        drawBox.draw(canvas);
      });
    }, 100);
});

}

function sendAttendanceDataToServer() {
  const attendanceData = [];

  document
    .querySelectorAll("#studentTableContainer tr")
    .forEach((row, index) => {
      if (index === 0) return;
      const studentID = row.cells[0].innerText.trim();
      const course = row.cells[2].innerText.trim();
      const unit = row.cells[3].innerText.trim();
      const attendanceStatus = row.cells[5].innerText.trim();

      attendanceData.push({ studentID, course, unit, attendanceStatus });
    });

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "handle_attendance", true);
  xhr.setRequestHeader("Content-Type", "application/json");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);

          if (response.status === "success") {
            showMessage(
              response.message || "Attendance recorded successfully."
            );
          } else {
            showMessage(
              response.message ||
                "An error occurred while recording attendance."
            );
          }
        } catch (e) {
          showMessage("Error: Failed to parse the response from the server.");
          console.error(e);
        }
      } else {
        showMessage(
          "Error: Unable to record attendance. HTTP Status: " + xhr.status
        );
        console.error("HTTP Error", xhr.status, xhr.statusText);
      }
    }
  };

  xhr.send(JSON.stringify(attendanceData));
}
function showMessage(message) {
  var messageDiv = document.getElementById("messageDiv");
  messageDiv.style.display = "block";
  messageDiv.innerHTML = message;
  console.log(message);
  messageDiv.style.opacity = 1;
  setTimeout(function () {
    messageDiv.style.opacity = 0;
  }, 5000);
}
function stopWebcam() {
  if (videoStream) {
    const tracks = videoStream.getTracks();

    tracks.forEach((track) => {
      track.stop();
    });

    video.srcObject = null;
    videoStream = null;
  }
}

document.getElementById("endAttendance").addEventListener("click", function () {
  sendAttendanceDataToServer();
  const videoContainer = document.querySelector(".video-container");
  videoContainer.style.display = "none";
  stopWebcam();
});
