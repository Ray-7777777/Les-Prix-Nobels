$(document).ready(function() {
    let currentImage = 0;
    const totalImages = $(".slide").length;

    $(".next").click(function() {
        currentImage = (currentImage + 1) % totalImages;
        updateImage();
    });

    $(".prev").click(function() {
        currentImage = (currentImage - 1 + totalImages) % totalImages;
        updateImage();
    });

    $(".selector-dot").click(function() {
        currentImage = $(this).data("index");
        updateImage();
    });

    function updateImage() {
        $(".slide").hide();
        $(".slide").eq(currentImage).fadeIn(500); 
        $(".image-title").hide(); 
        $(".image-title").eq(currentImage).show(); 
    }
});
