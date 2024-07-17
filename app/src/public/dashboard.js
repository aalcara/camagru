function clicou() {
  const canvas = document.getElementById("canvas");
  const ctx = canvas.getContext("2d");

  ctx.fillStyle = "green";
  ctx.fillRect(10, 10, 150, 100);
}

let showing = null;

function getCanvasContext() {
  const canvas = document.getElementById("canvas");
  const ctx = canvas.getContext("2d");
  return ctx;
}

function drawCanvas(ctx, img) {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  if (img != null) {
    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
  }
}

function showConfirmButton() {
  document.getElementById("confirmForm").style.display = "block";
}

function showCaptureButton() {
  document.getElementById("captureButton").style.display = "block";
}

function hideConfirmButton() {
  document.getElementById("confirmForm").style.display = "none";
}

function hideCaptureButton() {
  document.getElementById("captureButton").style.display = "none";
}

function stopWebcam(ctx) {
  const video = document.getElementById("webcamVideo");
  video.pause();
  video.srcObject.getTracks()[0].stop();
  document.body.removeChild(video);

  document.getElementById("webcamButton").innerHTML = "Use Your Webcam";
  hideCaptureButton();
  drawCanvas(ctx, null);
  showing = null;
}

function onActivateWebcam() {
  hideConfirmButton();
  const ctx = getCanvasContext();

  if (showing === "video") {
    stopWebcam(ctx);
    return;
  }
  video = document.createElement("video");
  video.id = "webcamVideo";
  video.style = "display:none;";
  document.body.appendChild(video);

  navigator.mediaDevices
    .getUserMedia({ video: true })
    .then((stream) => {
      video.srcObject = stream;
      video.play();

      function drawFrame() {
        if (showing === "video") {
          drawCanvas(ctx, video);
          requestAnimationFrame(drawFrame);
        }
      }

      document.getElementById("webcamButton").innerHTML = "Stop Webcam";
      showing = "video";
      showCaptureButton();
      drawFrame();
    })
    .catch((error) => {
      console.error("Error starting webcam: ", error);
    });
}

function onCaptureWebcam() {
  const ctx = getCanvasContext();

  const frame = canvas.toDataURL("image/png");
  stopWebcam(ctx);
  previewImage(frame);
}

function onUploadImage(event) {
  const file = event.target.files[0];
  const reader = new FileReader();
  reader.onload = function () {
    previewImage(reader.readAsDataURL(file));
  };
}

function previewImage(src) {
  const ctx = getCanvasContext();
  if (showing === "video") {
    stopWebcam(ctx);
  }
  showing = "image";
  const img = new Image();
  img.src = src;
  img.onload = function () {
    drawCanvas(ctx, img);
  };
  showConfirmButton();

  document.getElementById("tempImagePath").value = src;
}
