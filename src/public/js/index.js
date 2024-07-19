document.getElementById('pref-dropdown').addEventListener('focus', function() {
    this.children[0].style.display = 'none';
});

document.getElementById('pref-dropdown').addEventListener('blur', function() {
    if (this.value === "") {
        this.children[0].style.display = 'block';
    }
});

document.getElementById('genre-dropdown').addEventListener('focus', function() {
    this.children[0].style.display = 'none';
});

document.getElementById('genre-dropdown').addEventListener('blur', function() {
    if (this.value === "") {
        this.children[0].style.display = 'block';
    }
});

document.getElementById('likeButton').addEventListener('click', function() {
    if (this.classList.contains('liked')) {
        this.classList.remove('liked');
        this.classList.replace('las', 'lar');
    } else {
        this.classList.add('liked');
        this.classList.replace('lar', 'las');
    }
});
