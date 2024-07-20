let showing = null;
let animationFrameHandler = 0;

const canvas = document.getElementById("canvas");
const ctx = canvas.getContext("2d");

const backgroundCanvas = document.createElement("canvas");
backgroundCanvas.height = canvas.height;
backgroundCanvas.width = canvas.width;
const backgroundCtx = backgroundCanvas.getContext("2d");

let video;

function resizeImage(img) {
  let factorX = img.width / canvas.width;
  let factorY = img.height / canvas.height;
  let factor = factorX > factorY ? factorX : factorY;
  img.width = Math.floor(img.width / factor);
  img.height = Math.floor(img.height / factor);
  return img;
}

function drawCanvas(img) {
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

  if (video) {
    video.pause();
    video.srcObject.getTracks()[0].stop();
    document.body.removeChild(video);
    cancelAnimationFrame(animationFrameHandler);
    console.log("video", video);
  }

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
          const videoAspectRatio = video.videoWidth / video.videoHeight;
          const canvasAspectRatio = canvas.width / canvas.height;
          let drawWidth, drawHeight, offsetX, offsetY;
          if (videoAspectRatio > canvasAspectRatio) {
            drawWidth = canvas.width;
            drawHeight = canvas.width / videoAspectRatio;
            offsetX = 0;
            offsetY = (canvas.height - drawHeight) / 2;
          } else {
            drawWidth = canvas.height * videoAspectRatio;
            drawHeight = canvas.height;
            offsetX = (canvas.width - drawWidth) / 2;
            offsetY = 0;
          }

          ctx.clearRect(0, 0, canvas.width, canvas.height);
          ctx.drawImage(video, offsetX, offsetY, drawWidth, drawHeight);
          backgroundCtx.drawImage(
            video,
            offsetX,
            offsetY,
            drawWidth,
            drawHeight
          );
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
