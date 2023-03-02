// noinspection JSUnresolvedVariable,JSUnresolvedFunction

const cards = document.querySelectorAll('.card');
cards.forEach(card => {
  const plus = card.querySelector('.counter-plus');
  const minus = card.querySelector('.counter-minus');
  const quantity = card.querySelector('.quantity');
  plus.classList.add('active');
  plus.addEventListener('click', () => {
    quantity.innerText = parseInt(quantity.innerText) + 1;
    quantity.style.display = 'block';
    minus.style.display = 'inline-block';
    plus.classList.remove('active');
    toggleMainButton(true);
  });
  minus.addEventListener('click', () => {
    if (parseInt(quantity.innerText) > 0) {
      quantity.innerText = parseInt(quantity.innerText) - 1;
    }
    if (parseInt(quantity.innerText) === 0) {
      quantity.style.display = 'none';
      minus.style.display = 'none';
      plus.classList.add('active');
    }
    toggleMainButton();
  });
});

function toggleMainButton(display = false) {
  if (display) {
    App.MainButton.show();
  } else {
    let hidden = true;
    cards.forEach(card => {
      const quantity = card.querySelector('.quantity');
      if (parseInt(quantity.innerText) !== 0) {
        hidden = false;
      }
    })
    if (hidden) {
      App.MainButton.hide();
    }
  }
}

App.MainButton.onClick(() => {
  window.location.href = 'images/1.jpg'
})