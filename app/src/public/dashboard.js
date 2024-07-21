let showing = null;
let animationFrameHandler = 0;

const canvas = document.getElementById("canvas");
const ctx = canvas.getContext("2d");

const backgroundCanvas = document.createElement("canvas");
backgroundCanvas.height = canvas.height;
backgroundCanvas.width = canvas.width;
const backgroundCtx = backgroundCanvas.getContext("2d");

const superposableCanvas = document.createElement("canvas");
superposableCanvas.height = canvas.height;
superposableCanvas.width = canvas.width;
const superposableCtx = superposableCanvas.getContext("2d");

// document.body.append(backgroundCanvas);
// document.body.append(superposableCanvas);

let video;

function updateUploadButton() {
  let superposable = document.getElementById("superposableImage").value;
  let style = "none";
  if (showing === "image" && superposable != "") {
    style = "block";
  }
  document.getElementById("uploadForm").style.display = style;
}

function showCaptureButton() {
  document.getElementById("captureButton").style.display = "block";
}

function hideCaptureButton() {
  document.getElementById("captureButton").style.display = "none";
}

function resizeImage(img) {
  let factorX = img.width / canvas.width;
  let factorY = img.height / canvas.height;
  let factor = factorX > factorY ? factorX : factorY;
  img.width = Math.floor(img.width / factor);
  img.height = Math.floor(img.height / factor);
  return img;
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

function drawSuperposableCanvas(img) {
  superposableCtx.clearRect(0, 0, canvas.width, canvas.height);
  if (img != null) {
    superposableCtx.drawImage(
      img,
      (canvas.width - img.width) / 2,
      (canvas.height - img.height) / 2,
      img.width,
      img.height
    );
  }
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
  drawBackgroundCanvas(null);
  updateCanvas();
  showing = null;
}

function onActivateWebcam() {
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
          backgroundCtx.drawImage(
            video,
            offsetX,
            offsetY,
            drawWidth,
            drawHeight
          );
          updateCanvas();
          animationFrameHandler = requestAnimationFrame(drawFrame);
        }
      }

      document.getElementById("webcamButton").innerHTML = "Stop Webcam";
      showing = "video";
      updateUploadButton();
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
    updateCanvas();
    updateUploadButton();
    document.getElementById("tempImagePath").value =
      backgroundCanvas.toDataURL();
  };
}

function updateCanvas() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.drawImage(backgroundCanvas, 0, 0);
  ctx.drawImage(superposableCanvas, 0, 0);
}

function onSelectSuperposableImage() {
  let select = document.querySelector("select#superposableImages");
  if (!select) {
    return;
  }
  console.log(select.value);
  let imagePath = select.value;

  document.getElementById("superposableImage").value = imagePath;
  updateUploadButton();

  if (imagePath === "") {
    drawSuperposableCanvas(null);
    updateCanvas();
    return;
  }
  let img = new Image();
  img.src = imagePath;
  img.onload = function () {
    img = resizeImage(img);
    drawSuperposableCanvas(img);
    updateCanvas();
  };
}
