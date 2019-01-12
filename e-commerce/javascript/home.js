var slideIndex = 0;
showSlide();

function showSlide(){
	var i;
	var slide = document.getElementsByClassName("imageSlide");
	var circle = document.getElementsByClassName("circle");

	for(i=0; i<slide.length; i++){
		slide[i].style.display = "none";
	}
	
	slideIndex++;
	if(slideIndex > slide.length){ slideIndex = 1; }

	for(i=0; i<circle.length; i++){
		circle[i].className = circle[i].className.replace(" active", "");
	}

	slide[slideIndex-1].style.display = "block";
	circle[slideIndex-1].className += " active";
	setTimeout(showSlide, 2000); //Change image every 2 seconds
}