//function that hides or displays form
function createForm(parent) {
    //get necessary elements by their respective ids
    var button = document.getElementById("button"+parent);
    var form = document.getElementById("form" + parent);
    //if form is hidden - make form visible again and change button text
    if (button.firstChild.data == 'Hide form')
    {
        form.style.display = "none";
        button.firstChild.data = 'Add Category';
    }
    else {  //form is not visible - view it again and change button text

        form.style.display = "inline";
        button.firstChild.data = 'Hide form';
    }
}