document.getElementById("search-box").addEventListener("input", function (e) {
    if (this.value === "") {
        myFunction();
    }
});

function search() {
    input = document.getElementById("search-box");
    filter = input.value.toUpperCase();
    accordion = document.getElementById("accordion");
    accordionItem = accordion.getElementsByClassName("accordion-item");

    for (i = 0; i < accordionItem.length; i++) {
        student_name = accordionItem[i].getElementsByTagName("input")[0];
        student_group = accordionItem[i].getElementsByTagName("input")[1];
        inventory = accordionItem[i].getElementsByTagName("input")[4];

        if (student_name) {
            txtValue = student_name.placeholder;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                accordionItem[i].style.display = "";
            } else {
                if (student_group) {
                    txtValue = student_group.placeholder;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        accordionItem[i].style.display = "";
                    } else {
                        if (inventory) {
                            txtValue = inventory.placeholder;
                            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                accordionItem[i].style.display = "";
                            } else {
                                accordionItem[i].style.display = "none";
                            }
                        }
                    }
                }
            }
        }
    }
}