var lastTimestamp;
var keys = [];
var W_KEY = 87, A_KEY = 65, S_KEY = 83, D_KEY = 68, SPACE_KEY = 32;


var Player = {
    x: 0,
    y: 0,
    r: 10,
    color: "red",
    speed: 0,
    angularSpeed: 0,
    a: 0,
    angle: 0,
    board: null,


    // constructeur
    init: function (x, y, angle, color, board) {
        this.x = x;
        this.y = y;
        this.angle = angle;
        this.color = color;
        this.board = board;
        this.a = 0;
        this.angularSpeed = 0;
    },

    update: function (deltaT) {
        this.speed = this.a === -1 ? this.speed - 5 : this.a === 1 ? this.speed + 5 : this.speed;

        this.angle = this.angle + (Math.PI * 2) * deltaT * this.angularSpeed;
        this.x = this.x + deltaT * (this.speed) * Math.cos(this.angle);
        this.y = this.y + deltaT * (this.speed) * Math.sin(this.angle);

        this.angularSpeed = 0;
        this.a = this.a === -1 && this.speed > 0 ? this.a : 0

    },

    render: function () {
        var ctx = this.board;
        // mob drawing
        ctx.beginPath();
        ctx.fillStyle = this.color;
        ctx.arc(this.x, this.y, this.r, 0, 360);
        ctx.closePath();
        ctx.fill();

        // draw direction indicator
        var dx = this.x + 1.5 * this.r * Math.cos(this.angle);
        var dy = this.y + 1.5 * this.r * Math.sin(this.angle);
        ctx.beginPath();
        ctx.strokeStyle = this.color;
        ctx.lineWidth = 3;
        ctx.moveTo(this.x, this.y);
        ctx.lineTo(dx, dy);
        ctx.closePath();
        ctx.stroke();
    },

    rotateClockwise: function () {
        this.angularSpeed = 1;
    },

    rotateReverseClockwise: function () {
        this.angularSpeed = -1;
    },

    accelerate: function () {
        this.a = 1;
    },

    decelerate: function () {
        if (this.speed > 0)
            this.a = -1;
    },

    shoot: function () {

    },
};

window.onload = function () {
    var board = document.getElementById("gameboard").getContext("2d");
    var player1 = Object.create(Player);
    var player2 = Object.create(Player);
    player1.init(150, 100, 0, "red", board);
    player2.init(50, 100, 3.141592653589793383, "blue", board);

    // GAME LOOP
    function mainLoop(timestamp) {
        var deltaT = (timestamp - lastTimestamp) / 1000;
        lastTimestamp = timestamp;
        requestAnimationFrame(mainLoop);

        if (keys[W_KEY])
            player1.accelerate();

        if (keys[S_KEY] || !keys[W_KEY])
            player1.decelerate()

        if (keys[A_KEY])
            player1.rotateReverseClockwise();

        if (keys[D_KEY])
            player1.rotateClockwise();

        player1.update(deltaT);
        player2.update(deltaT);
        board.clearRect(0, 0, board.canvas.width, board.canvas.height);
        player1.render();
        player2.render();
    }

    document.body.addEventListener("keydown", function (e) {
        console.log(e.keyCode);
        keys[e.keyCode] = true;
    });
    document.body.addEventListener("keyup", function (e) {
        keys[e.keyCode] = false;
    });

    lastTimestamp = performance.now();
    requestAnimationFrame(mainLoop);
};