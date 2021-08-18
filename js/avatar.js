'use strict';

var strangeHash = function strangeHash(str) {
    var hash = 0;

    if (str.length === 0) {
        return hash;
    }

    for (var i = 0, len = str.length; i < len; i++) {
        var chr = str.charCodeAt(i);
        hash = (hash << 5) - hash + chr;
        hash |= 0;
    }

    return (new Array(9).join('1') + Math.abs(hash)).slice(-8).replace(/0/g, '1'); // Generate string with 8 chars without '0'
};

[].forEach.call(document.querySelectorAll('canvas.avatar'), function(c) {
    var ctx = c.getContext('2d'),
        h = strangeHash(c.getAttribute('data-initial'));

    ctx.translate(32, 32);
    ctx.rotate(Math.PI / 4);

    for (var j = 0; j < 8; j++) {
        // Colors (Red, Green, Blue, Alpha) is here.
        var r = h[0] * 100 % 255,
            // This formula doesn't make sense
            g = h[j] * 100 % 255,
            // And this too...
            b = h[8 - j] * 100 % 255,
            // Too many strange ideas in this generator :)
            a = h[j] / 20;

        ctx.fillStyle = 'rgba(' + r + ',' + g + ',' + b + ',' + a + ')';

        // Coordinates of rectangle. Another strange thing. But result looks fine...
        var c1 = h[j] * h[j] % 16,
            c2 = h[j] * h[7] % 16,
            c3 = h[j] * h[0] % 32,
            c4 = h[j] * h[0] % 32;

        // Some "normalization"
        if (c1 + c2 < 32) {
            c1 *= -2;
            c2 *= -2;
            c3 *= 2;
            c4 *= 2;
        }

        // Some experiments here......
        var s = h[7] % 2 + 2;

        for (var k = 0; k < 2 * s; k++) {
            ctx.fillRect(c1, c2, c3, c4);
            ctx.rotate(Math.PI / s);
        }
    }
});