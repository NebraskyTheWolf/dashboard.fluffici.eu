let colorArray = ["#EF5350", "#EC407A","#AB47BC","#7E57C2","#5C6BC0","#42A5F5","#29B6F6","#26C6DA","#26A69A","#66BB6A","#9CCC65","#D4E157","#FFEE58","#FFCA28","#FFA726","#FF7043","#8D6E63","#BDBDBD","#78909C"],
    confetti = document.getElementById("confetti"),
    container = document.getElementById("container")

gsap.set('svg', {
    visibility: 'visible'
})
let confettiTl = gsap.timeline({paused: true});

function playConfetti () {
    confettiTl.play(0);
}

function createConfetti () {
    var i = 160, clone, tl, rot, duration, paperDuration;
    while(--i > -1) {
        tl = gsap.timeline();
        clone = confetti.cloneNode(true);
        container.appendChild(clone);
        rot = gsap.utils.random(0, 360);
        duration = gsap.utils.random(3, 9);
        paperDuration = (duration)/20;
        gsap.set(clone, {
            fill: gsap.utils.random(colorArray),
            rotation: rot,
            transformOrigin: '50% 50%'
        })

        tl.fromTo(clone, {
            x: gsap.utils.random(0, 800),
            y: gsap.utils.random(-250, -50),
            rotation: rot
        }, {
            duration: duration,
            x: '+=' + gsap.utils.random(-200, 200),
            y: 650,
            rotation: '+=180',
            ease: 'linear'
        })
            .to(clone.querySelector('.paper'), {
                duration: duration/23,
                scaleY: 0.1,
                repeat: 23,
                yoyo: true
            }, 0)
        //console.log(paperDuration)
        confettiTl.add(tl, i/200).timeScale(2.3);
    }

    gsap.set('.paper', {
        transformOrigin: '50% 50%'
    })
}

function startAnimation() {
    let tl = gsap.timeline({repeat: -1});
    tl.from('#dot', {
        scale: 0,
        transformOrigin: '50% 50%',
        ease: 'elastic(0.4, 0.5)'
    }, 'step3+=0.46')
        .add(playConfetti, 'step3+=0.46')
        .add('step4', '+=2')
        .to('#one', {
            morphSVG: {
                shape: "#one_mid"
            },
            duration: 30
        }, 'step4')


    createConfetti();
}
