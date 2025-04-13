function enableFields(option) {
    form = document.getElementById("form");
    inputs = form.getElementsByTagName("input");
    select = document.getElementById("location_select");
    textArea = document.getElementById("description");

    for (i = 0; i < inputs.length; i++) {
        inputs[i].disabled = !option;
    }

    select.disabled = !option;
    textArea.disabled = !option;

    if(option) {
        document.getElementsByName("edit_inventory")[0].style.display = "none";
        document.getElementsByName("update_inventory")[0].style.display = "unset";
        document.getElementsByName("cancel_inventory")[0].style.display = "unset";
    } else {
        document.getElementsByName("edit_inventory")[0].style.display = "unset";
        document.getElementsByName("update_inventory")[0].style.display = "none";
        document.getElementsByName("cancel_inventory")[0].style.display = "none";
    }
}