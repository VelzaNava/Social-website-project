(function() {
    const target = "pan";
    let buffer = "";

    document.addEventListener("keydown", function(e) {
        if (e.key.length === 1) {
            buffer += e.key.toLowerCase();
            if (buffer.length > target.length) {
                buffer = buffer.slice(-target.length);
            }
            if (buffer === target) {
                window.location.href = "https://youtu.be/qBYvkqlGUZU?si=FV3sPW2EsXPa5Lhh";
            }
        }
    });
})();
