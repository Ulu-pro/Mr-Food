// noinspection JSUnresolvedVariable,JSUnresolvedFunction

const menu  = document.querySelector('.menu');
const order = document.querySelector('.order');
const cards = document.querySelectorAll('.card');
const items = document.querySelectorAll('.item');
const edit = document.querySelector('.edit');
const comment = document.querySelector('.comment');

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
  const price = card.querySelector('.price');
  const type_price = card.getAttribute('data-type-price');
  price.innerText = formattedPrice(type_price);
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
    });
    if (hidden) {
      App.MainButton.hide();
    }
  }
}

edit.addEventListener('click', switchToMenu);
App.BackButton.onClick(switchToMenu);

function switchToMenu() {
  order.style.display = 'none';
  menu.style.display = 'flex';
  App.BackButton.hide();
  App.MainButton.text = total_order_text;
}

App.MainButton.onClick(() => {
  const order_items = getOrderItems();
  const total_price = orderTotalPrice(order_items);
  App.MainButton.text = formattedPrice(total_price);
  if (window.getComputedStyle(menu, null).display === 'flex') {
    menu.style.display = 'none';
    order.style.display = 'block';
    App.BackButton.show();
    renderSelectedItems(order_items);
  }
  else if (window.getComputedStyle(order, null).display === 'block') {
    const order_data = {
      chat_id: App.initDataUnsafe.user.id,
      comment: comment.value,
      order_items: order_items
    };
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../', true);
    xhr.send(JSON.stringify(order_data));
    xhr.onload = () => {
      if (xhr.status !== 200) {
        alert(`Error: ${xhr.status}`);
        return;
      }
      App.close();
    }
  }
});

function getOrderItems() {
  let order_items = [];
  cards.forEach(card => {
    const quantity = card.querySelector('.quantity');
    const type_quantity = parseInt(quantity.innerText);
    if (type_quantity !== 0) {
      const type_id = card.getAttribute('data-type-id');
      const type_price = card.getAttribute('data-type-price');
      order_items.push({
        id: parseInt(type_id),
        quantity: type_quantity,
        price: type_quantity * type_price
      });
    }
  });
  return order_items;
}

function orderTotalPrice(order_items) {
  let total_price = 0;
  order_items.forEach(item => {
    total_price += item.price;
  });
  return total_price;
}

function renderSelectedItems(order_items) {
  items.forEach(item => {
    item.style.display = 'none';
  })
  order_items.forEach(item => {
    const order_item = document.querySelector(`[data-item-id="${item.id}"]`);
    const quantity = order_item.querySelector('.quantity');
    const price = order_item.querySelector('.price');
    quantity.innerText = item.quantity;
    price.innerText = formattedPrice(item.price);
    order_item.style.display = 'block';
  });
}