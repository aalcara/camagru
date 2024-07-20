<h1>
	Dashboard
</h1>
<input type="file" id="imageUpload" name="image" accept="image/*" onchange="onUploadImage(event)" style="display:none;">
<button type="button" onclick="document.getElementById('imageUpload').click()">Choose File</button>
OR
<button id="webcamButton" onclick="onActivateWebcam()">Use Your Webcam</button>
<br>
<canvas id="canvas" width="<?php echo $data['canvasWidth']; ?>" height="<?php echo $data['canvasHeight']; ?>"></canvas>
<br>
<button id="captureButton" onclick="onCaptureWebcam()" style="display:none;">Capture</button>

<form id="confirmForm" action="dashboard/upload" method="post" style="display:none;">
	<input type="hidden" id="tempImagePath" name="image">
	<button type="submit">Upload Image</button>
</form>
<script src="../dashboard.js"></script>