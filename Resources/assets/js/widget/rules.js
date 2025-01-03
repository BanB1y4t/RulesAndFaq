let rule = document.querySelectorAll('.rule');

rule.forEach((rule) => {
    rule.addEventListener('click', (event) => {
        const active = document.querySelector('.rule.active');
        if (active && active !== rule) {
            active.classList.toggle('active');
            active.nextElementSibling.style.maxHeight = 0;
        }
        rule.classList.toggle('active');
        const answer = rule.nextElementSibling;
        if (rule.classList.contains('active')) {
            answer.style.maxHeight = answer.scrollHeight + 'px';
        } else {
            answer.style.maxHeight = 0;
        }
    });
});
