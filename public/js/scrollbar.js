const slider = document.querySelector('.slider-inner');
const progressBar = document.querySelector('.prog-bar-inner');

let sliderGrabbed = false;


slider.addEventListener('mousedown', (e) => {
    sliderGrabbed = true;
    slider.style.cursor = 'grabbing';
})

slider.addEventListener('mouseup', (e) => {
    sliderGrabbed = false;
    slider.style.cursor = 'grab';
})

slider.addEventListener('mouseleave', (e) => {
    sliderGrabbed = false;
})

slider.addEventListener('mousemove', (e) => {
    if(sliderGrabbed){
        slider.parentElement.scrollLeft -= e.movementX;
    }
})

function getScrollPercentage(){
   return ((slider.parentElement.scrollLeft / (slider.parentElement.scrollWidth - slider.parentElement.clientWidth) ) * 100);
}

document.addEventListener('keydown', preventKeyBoardScroll, false);

function preventKeyBoardScroll(e) {
  var keys = [32, 33, 34, 35, 37, 38, 39, 40];
  if (keys.includes(e.keyCode)) {
    e.preventDefault();
    return false;
  }
}









// numéro 2 
const slider2 = document.querySelector('.slider-inner2');
const progressBar2 = document.querySelector('.prog-bar-inner2');

let sliderGrabbed2 = false;


slider2.addEventListener('mousedown', (e) => {
    sliderGrabbed2 = true;
    slider2.style.cursor = 'grabbing';
})

slider2.addEventListener('mouseup', (e) => {
    sliderGrabbed2 = false;
    slider2.style.cursor = 'grab';
})

slider2.addEventListener('mouseleave', (e) => {
    sliderGrabbed2 = false;
})

slider2.addEventListener('mousemove', (e) => {
    if(sliderGrabbed2){
        slider2.parentElement.scrollLeft -= e.movementX;
    }
})

function getScrollPercentage(){
   return ((slider2.parentElement.scrollLeft / (slider2.parentElement.scrollWidth - slider2.parentElement.clientWidth) ) * 100);
}

document.addEventListener('keydown', preventKeyBoardScroll, false);

function preventKeyBoardScroll(e) {
  var keys = [32, 33, 34, 35, 37, 38, 39, 40];
  if (keys.includes(e.keyCode)) {
    e.preventDefault();
    return false;
  }
}




// numéro 3 
const slider3 = document.querySelector('.slider-inner3');
const progressBar3 = document.querySelector('.prog-bar-inner3');

let sliderGrabbed3 = false;


slider3.addEventListener('mousedown', (e) => {
    sliderGrabbed3 = true;
    slider3.style.cursor = 'grabbing';
})

slider3.addEventListener('mouseup', (e) => {
    sliderGrabbed3 = false;
    slider3.style.cursor = 'grab';
})

slider3.addEventListener('mouseleave', (e) => {
    sliderGrabbed3 = false;
})

slider3.addEventListener('mousemove', (e) => {
    if(sliderGrabbed3){
        slider3.parentElement.scrollLeft -= e.movementX;
    }
})

function getScrollPercentage(){
   return ((slider3.parentElement.scrollLeft / (slider3.parentElement.scrollWidth - slider3.parentElement.clientWidth) ) * 100);
}

document.addEventListener('keydown', preventKeyBoardScroll, false);

function preventKeyBoardScroll(e) {
  var keys = [32, 33, 34, 35, 37, 38, 39, 40];
  if (keys.includes(e.keyCode)) {
    e.preventDefault();
    return false;
  }
}



// numéro 5
const slider5 = document.querySelector('.slider-inner5');
const progressBar5 = document.querySelector('.prog-bar-inner5');

let sliderGrabbed5 = false;


slider5.addEventListener('mousedown', (e) => {
    sliderGrabbed5 = true;
    slider5.style.cursor = 'grabbing';
})

slider5.addEventListener('mouseup', (e) => {
    sliderGrabbed5 = false;
    slider5.style.cursor = 'grab';
})

slider5.addEventListener('mouseleave', (e) => {
    sliderGrabbed5 = false;
})

slider5.addEventListener('mousemove', (e) => {
    if(sliderGrabbed5){
        slider5.parentElement.scrollLeft -= e.movementX;
    }
})

function getScrollPercentage(){
   return ((slider5.parentElement.scrollLeft / (slider5.parentElement.scrollWidth - slider5.parentElement.clientWidth) ) * 100);
}

document.addEventListener('keydown', preventKeyBoardScroll, false);

function preventKeyBoardScroll(e) {
  var keys = [32, 33, 34, 35, 37, 38, 39, 40];
  if (keys.includes(e.keyCode)) {
    e.preventDefault();
    return false;
  }
}



// numéro 4
const slider4 = document.querySelector('.slider-inner4');
const progressBar4 = document.querySelector('.prog-bar-inner4');

let sliderGrabbed4 = false;


slider4.addEventListener('mousedown', (e) => {
    sliderGrabbed4 = true;
    slider4.style.cursor = 'grabbing';
})

slider4.addEventListener('mouseup', (e) => {
    sliderGrabbed4 = false;
    slider4.style.cursor = 'grab';
})

slider4.addEventListener('mouseleave', (e) => {
    sliderGrabbed4 = false;
})

slider4.addEventListener('mousemove', (e) => {
    if(sliderGrabbed4){
        slider4.parentElement.scrollLeft -= e.movementX;
    }
})

function getScrollPercentage(){
   return ((slider4.parentElement.scrollLeft / (slider4.parentElement.scrollWidth - slider4.parentElement.clientWidth) ) * 100);
}

document.addEventListener('keydown', preventKeyBoardScroll, false);

function preventKeyBoardScroll(e) {
  var keys = [32, 33, 34, 35, 37, 38, 39, 40];
  if (keys.includes(e.keyCode)) {
    e.preventDefault();
    return false;
  }
}
