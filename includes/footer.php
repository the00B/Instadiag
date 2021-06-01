<script>
    function scroll() {
        var top = document.getElementById("barra");
        var aside = document.getElementById("aside");
        var ypos = window.pageYOffset;

        if (ypos > 54) {
            top.style.height = "52px";
            top.style.lineHeight = "52px";
        } else {
            top.style.height = "77px";
            top.style.lineHeight = "77px";
            aside.style.position = "";
        }
    }

    window.addEventListener("scroll", scroll);
</script>

<script src="assets/js/buttonActions.js"></script>
<script src="assets/js/commentActions.js"></script>


</body>

</html>