// Put a page break after the label to match the rest of the style.
document.addEventListener('DOMContentLoaded', function () {
    var br = document.createElement('BR');
    document.getElementById('wpas_tags').labels[0].after(br);
}, false);