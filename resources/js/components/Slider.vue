<template>
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="6"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="7"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="8"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100"
                     :src="'https://picsum.photos/800/300/?random&img=' + Math.random()"
                     alt="First slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100"
                     :src="'https://picsum.photos/800/300/?random&img=' + Math.random()"
                     alt="Second slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100"
                     :src="'https://picsum.photos/800/300/?random&img=' + Math.random()"
                     alt="Third slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100"
                     :src="'https://picsum.photos/800/300/?random&img=' + Math.random()"
                     alt="Four slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100"
                     :src="'https://picsum.photos/800/300/?random&img=' + Math.random()"
                     alt="Fifth slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100"
                     :src="'https://picsum.photos/800/300/?random&img=' + Math.random()"
                     alt="Sixth slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100"
                     :src="'https://picsum.photos/800/300/?random&img=' + Math.random()"
                     alt="Seventh slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100"
                     :src="'https://picsum.photos/800/300/?random&img=' + Math.random()"
                     alt="Eighth slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100"
                     :src="'https://picsum.photos/800/300/?random&img=' + Math.random()"
                     alt="Ninth slide">
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "Slider"
    }
</script>

<style scoped>
    /* section 1 slider */
    .slider-image-1 {
        position: relative;
        width: 100%;
        max-width: 1920px;
        height: 100%;
        max-height: 968px;
        object-fit: contain;
    }

    .carousel-indicators {
        bottom: 0%;
        left: 96%;
        width: 0.72%;
        height: 30%;
        display: block;
        margin: 2%;
        background: transparent;
        flex-direction: row;
        opacity: 0.9;
    }

    .carousel-indicators li {
        width: 100%;
        height: 8%;
        border: 0.15px solid white;
        margin: 0 0 0.72% 0;
        background: transparent;
        opacity: 0.5;
    }

    .carousel-indicators .active {
        background: white;
        opacity: 0.9;
    }

</style>