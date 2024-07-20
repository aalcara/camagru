function clicou() {
  const canvas = document.getElementById("canvas");
  const ctx = canvas.getContext("2d");

  ctx.fillStyle = "green";
  ctx.fillRect(10, 10, 150, 100);
}

let showing = null;
let animationFrameHandler = 0;
const canvas = document.getElementById("canvas");
const backgroundCanvas = document.createElement("canvas");
backgroundCanvas.height = canvas.height;
backgroundCanvas.width = canvas.width;
const backgroundCtx = backgroundCanvas.getContext("2d");

function resizeImage(img) {
  let factorX = img.width / canvas.width;
  let factorY = img.height / canvas.height;
  let factor = factorX > factorY ? factorX : factorY;
  img.width = Math.floor(img.width / factor);
  img.height = Math.floor(img.height / factor);
  return img;
}

function drawCanvas(img) {
  const ctx = canvas.getContext("2d");
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  if (img != null) {
    ctx.drawImage(
      img,
      (canvas.width - img.width) / 2,
      (canvas.height - img.height) / 2,
      img.width,
      img.height
    );
  }
}

function drawBackgroundCanvas(img) {
  backgroundCtx.clearRect(0, 0, canvas.width, canvas.height);
  if (img != null) {

    backgroundCtx.drawImage(
      img,
      (canvas.width - img.width) / 2,
      (canvas.height - img.height) / 2,
      img.width,
      img.height
    );
  }
}

function updateImageToUpload(src) {
  document.getElementById("tempImagePath").value = src;
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

function stopWebcam() {
  const video = document.getElementById("webcamVideo");
  video.pause();
  video.srcObject.getTracks()[0].stop();
  document.body.removeChild(video);
  cancelAnimationFrame(animationFrameHandler);

  document.getElementById("webcamButton").innerHTML = "Use Your Webcam";
  hideCaptureButton();
  drawCanvas(null);
  showing = null;
}

function onActivateWebcam() {
  hideConfirmButton();

  if (showing === "video") {
    stopWebcam();
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
          // drawCanvas(video);
          const ctx = canvas.getContext("2d");
          ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
          animationFrameHandler = requestAnimationFrame(drawFrame);
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
  const video = document.getElementById("webcamVideo");
  backgroundCtx.drawImage(
    video,
    0,
    0,
    backgroundCanvas.width,
    backgroundCanvas.height
  );
  const frame = backgroundCanvas.toDataURL("image/png");
  stopWebcam();
  previewImage(frame);
}

function onUploadImage(event) {
  const file = event.target.files[0];
  const reader = new FileReader();
  reader.readAsDataURL(file);
  reader.onload = function () {
    previewImage(reader.result);
  };
}

function previewImage(src) {
  const ctx = getCanvasContext();
  if (showing === "video") {
    stopWebcam();
  }
  showing = "image";
  let img = new Image();
  img.src = src;
  img.onload = function () {
    img = resizeImage(img);
    drawBackgroundCanvas(img);
    drawCanvas(img);
    showConfirmButton();
    updateImageToUpload(backgroundCanvas.toDataURL());
  };
}
