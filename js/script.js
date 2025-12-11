

let navber = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btn').onclick = () => {
    navber.classList.toggle('active');
    
    
}

window.onscroll = () => {
    navber.classList.remove('active');

}

document.querySelectorAll('input[type="number"]').forEach(inputNumber => {

    inputNumber.oninput = () => {
        if (inputNumber.value.length > inputNumber.maxLength) inputNumber.value = inputNumber.value.slice(0, inputNumber.maxLength);

    };
});

