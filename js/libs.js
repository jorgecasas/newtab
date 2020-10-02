
var Newtab_Controller = {};

Newtab_Controller.filter=function( class_name ){
    let filter_value = (document.getElementById("filter").value).toLowerCase(); 
    let array_elements = document.getElementsByClassName(class_name); 
    Array.from(array_elements).forEach( function(element, index) {
        let inner_text = element.innerText.toLowerCase();
        let title_text = (element.getAttribute && element.getAttribute('title'))?element.getAttribute('title').toLowerCase():'';
        if (inner_text === '') {
        	inner_text = title_text;
        }
        let selected = inner_text.search(filter_value);
        if(selected>= 0){
            element.style.opacity = 1;
            element.removeAttribute("tabindex");
        }else{
            element.style.opacity = 0.125;
            element.setAttribute("tabindex","-1");
        }
    });

}