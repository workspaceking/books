window.onload = function () {
    var data = '<div id="lightbox-modal" class="modal"><span class="close">&times;</span><img class="modal-content" id="modal-image"><div id="caption"></div> </div> ';





    document.querySelector(".birds-slideshow").innerHTML += data;
}
function previewImage(e) {
    var modal = document.getElementById("lightbox-modal");

    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var modalImage = document.querySelectorAll('.birds-list div.image');
    var modalImg = document.getElementById("modal-image");
    var captionText = document.getElementById("caption");


    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        modal.style.display = "none";
    }
    modal.style.display = "block";
    modalImg.src = e.getAttribute('data');
    captionText.innerHTML = e.getAttribute('alt');
    console.log(e.getAttribute('data'))

} 

