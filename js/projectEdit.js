var editable = document.querySelectorAll('[contentEditable=true]');

for (var i=0, len = editable.length; i<len; i++){
    editable[i].setAttribute('data-orig',editable[i].innerHTML);

    editable[i].onblur = function(){
        if (this.innerHTML == this.getAttribute('data-orig')) {
            // no change
        }
        else {
            // change has happened, store new value
            this.setAttribute('data-orig',this.innerHTML);
            var hidden_form = document.getElementById("project_edit_form");
            x.elements[i].value = this.innerHTML;
        }
    };
}
