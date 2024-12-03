document.getElementById('role-dropdown').addEventListener('focus', function () {
    this.children[0].style.display = 'none';
});

document.getElementById('role-dropdown').addEventListener('blur', function () {
    if (this.value === "") {
        this.children[0].style.display = 'block';
    }
});
