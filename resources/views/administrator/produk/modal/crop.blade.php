<!-- Modal Crop -->
<div class="modal fade" id="ModalCrop" tabindex="-1" aria-labelledby="ModalCropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalCropLabel">Crop Image</h5>
                <button type="button" id="buttonCloseCropModal" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body" id="ModalCropBody">
                <div class="img-container">
                    <img id="image" src="" alt="Picture">
                </div>
                <p>Data: <span id="data"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="selectDataCrop">Crop</button>
                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>
@push('css')
    <style>
        .img-container,
        .img-preview {
        background-color: #f7f7f7;
        text-align: center;
        width: 100%;
        }

        .img-container {
        max-height: 497px;
        min-height: 200px;
        }

        @media (min-width: 768px) {
        .img-container {
            min-height: 497px;
        }
        }

        .img-container > img {
        max-width: 100%;
        }
    </style>
@endpush
@push('js')
    <script type="text/javascript">
        $('#ModalCrop').on('show.bs.modal', function(event) {
            var triggerCrop = $(event.relatedTarget);

            // Get the Cropper instance
            var cropper = $('#image').data('cropper');

            // Remove the Cropper instance data and attributes
            $('#image').removeData('cropper').removeAttr('src');

            // Clone the original image to reset it
            var originalImage = $('#image').clone();
            $('#ModalCropBody .img-container').empty().append(originalImage);

            let srcImage = triggerCrop.data('src')

            var image = $('#image')[0];
            image.src = srcImage

            var data = $('#data');
            var button = $('#button');
            var result = $('#result');
            var aspectRatio = 2 / 3;
            var cropper = new Cropper(image, {
                viewMode: 1,
                zoomable: false,
                aspectRatio: aspectRatio,

                data: {
                    width: 400, // Set an initial width
                    height: 600, // Set an initial height
                },

                crop: function (event) {
                    var width = Math.round(event.detail.width);
                    var height = Math.round(event.detail.height);

                    // Remove the maximum constraints
                    cropper.setData({
                        width: width,
                        height: height,
                    });

                    data.text(JSON.stringify(cropper.getData(true)));
                },
            });
            
            $('#selectDataCrop').off().on('click', function() {
                let datas = JSON.stringify(cropper.getData(true));
                let widthPercentage = 30; // Set the desired percentage

                // Set the values for the corresponding elements
                triggerCrop.parent().parent().find('.width-item').val(Math.round(cropper.getData().width));
                triggerCrop.parent().parent().find('.height-item').val(Math.round(cropper.getData().height));
                triggerCrop.parent().parent().find('.x-item').val(Math.round(cropper.getData().x));
                triggerCrop.parent().parent().find('.y-item').val(Math.round(cropper.getData().y));

                // Get the original canvas
                let originalCanvas = cropper.getCroppedCanvas();

                // Create a new canvas with 30% width and height
                let newCanvas = document.createElement('canvas');
                newCanvas.width = originalCanvas.width * (widthPercentage / 100);
                newCanvas.height = originalCanvas.height * (widthPercentage / 100);

                // Draw the original image onto the new canvas
                let ctx = newCanvas.getContext('2d');
                ctx.drawImage(originalCanvas, 0, 0, newCanvas.width, newCanvas.height);

                // Replace the content of the .img-thumbnail-container with the new canvas
                triggerCrop.parent().parent().find('.img-thumbnail-container canvas').remove();
                triggerCrop.parent().parent().find('.img-thumbnail-container').html(newCanvas);
                triggerCrop.parent().parent().find('.img-thumbnail-container canvas').addClass('img-thumbnail');

                $('#buttonCloseCropModal').click();
            });


            
            // end click Select button
        });
    </script>
@endpush
