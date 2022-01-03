function addEventListeners() {
    let itemCheckers = document.querySelectorAll('article.card li.item input[type=checkbox]');
    [].forEach.call(itemCheckers, function(checker) {
    checker.addEventListener('change', sendItemUpdateRequest);
    });

    let itemCreators = document.querySelectorAll('article.card form.new_item');
    [].forEach.call(itemCreators, function(creator) {
    creator.addEventListener('submit', sendCreateItemRequest);
    });

    let itemDeleters = document.querySelectorAll('article.card li a.delete');
    [].forEach.call(itemDeleters, function(deleter) {
    deleter.addEventListener('click', sendDeleteItemRequest);
    });

    let cardDeleters = document.querySelectorAll('article.card header a.delete');
    [].forEach.call(cardDeleters, function(deleter) {
    deleter.addEventListener('click', sendDeleteCardRequest);
    });

    let cardCreator = document.querySelector('article.card form.new_card');
    if (cardCreator != null)
    cardCreator.addEventListener('submit', sendCreateCardRequest);

    let likeToggles = document.querySelectorAll('div.like');
    [].forEach.call(likeToggles, function(like) {
        like.addEventListener('click', toggleLike);
    });

    let tagSearchButton = document.querySelector("button.tag_search_button");
    if (tagSearchButton != null)
        tagSearchButton.addEventListener('click', searchUser);
}

function encodeForAjax(data) {
  if (data == null) return null;
  return Object.keys(data).map(function(k){
    return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
  }).join('&');
}

function sendAjaxRequest(method, url, data, handler) {
  let request = new XMLHttpRequest();

  request.open(method, url, true);
  request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.addEventListener('load', handler);
  request.send(encodeForAjax(data));
}

function toggleLike() {
    let likeButton = this.closest('div.like');
    let likeImage = this.getElementsByTagName('div')[0].getElementsByTagName('img')[0];
    let user_id = likeButton.getAttribute('user_id');
    let content_id = likeButton.getAttribute('content_id');
    let liked = likeButton.getAttribute('liked');

    if(!liked) {
        sendAjaxRequest('post', '/api/likes', {user_id: user_id, content_id: content_id}, null);
        likeButton.setAttribute('liked', '1');
        likeImage.setAttribute('src', '/storage/graphics/full_heart.png');
        this.getElementsByTagName('div')[1].innerHTML = parseInt(this.getElementsByTagName('div')[1].innerHTML) + 1;
    }
    else {
        sendAjaxRequest('post', '/api/likes', {user_id: user_id, content_id: content_id, '_method': 'DELETE'}, null);
        likeButton.setAttribute('liked', '');
        likeImage.setAttribute('src', '/storage/graphics/empty_heart.png');
        this.getElementsByTagName('div')[1].innerHTML = parseInt(this.getElementsByTagName('div')[1].innerHTML) - 1;
    }
}

function searchUser() {
    let searchBoxes = document.getElementsByClassName('tag_search_field');
    let searchValue = searchBoxes[searchBoxes.length - 1].value;

    sendAjaxRequest('post', '/api/search/users', {searchString: searchValue}, searchHandler);
}

function searchHandler() {
    let users = JSON.parse(this.responseText);

    let tagLabel = document.getElementById('tag_label');

    let searchResults = document.createElement('table');

    searchResults.setAttribute('id', 'tag_search_results');
    searchResults.classList.add('search_results_table');

    users.forEach(function (user) {
        let row = document.createElement('tr');
        row.classList.add('search_results_row');

        let anchor = document.createElement('a');
        anchor.innerHTML = user.name;
        anchor.setAttribute('href', '/users/' + user.id);
        anchor.classList.add('search_results_row_name');

        let addTagButton = document.createElement('button');
        addTagButton.setAttribute('class', 'btn btn-primary');
        addTagButton.setAttribute('type', 'button');
        addTagButton.classList.add('search_results_row_tag');
        addTagButton.innerHTML = 'Tag';
        addTagButton.setAttribute('user_id', user.id);
        addTagButton.addEventListener('click', addTag);

        row.appendChild(anchor);
        row.appendChild(addTagButton);
        searchResults.appendChild(row);
    })

    let previousResults = document.getElementById('tag_search_results');

    if (previousResults != null) tagLabel.removeChild(previousResults);

    tagLabel.appendChild(searchResults);
}

function addTag() {
    let newTag = document.createElement('input');
    newTag.setAttribute('type', 'hidden');
    newTag.setAttribute('name', 'tags[]');
    newTag.setAttribute('id', 'tag_' + this.getAttribute('user_id'));
    newTag.setAttribute('value', this.getAttribute('user_id'));

    if (!document.getElementById('tag_' + this.getAttribute('user_id')))
        document.getElementById('create_post_form').appendChild(newTag);

    this.style.backgroundColor = 'grey';
    this.removeEventListener('click', addTag);
}

function sendItemUpdateRequest() {
  let item = this.closest('li.item');
  let id = item.getAttribute('data-id');
  let checked = item.querySelector('input[type=checkbox]').checked;

  sendAjaxRequest('post', '/api/item/' + id, {done: checked}, itemUpdatedHandler);
}

function sendDeleteItemRequest() {
  let id = this.closest('li.item').getAttribute('data-id');

  sendAjaxRequest('delete', '/api/item/' + id, null, itemDeletedHandler);
}

function sendCreateItemRequest(event) {
  let id = this.closest('article').getAttribute('data-id');
  let description = this.querySelector('input[name=description]').value;

  if (description != '')
    sendAjaxRequest('put', '/api/cards/' + id, {description: description}, itemAddedHandler);

  event.preventDefault();
}

function sendDeleteCardRequest(event) {
  let id = this.closest('article').getAttribute('data-id');

  sendAjaxRequest('delete', '/api/cards/' + id, null, cardDeletedHandler);
}

function sendCreateCardRequest(event) {
  let name = this.querySelector('input[name=name]').value;

  if (name != '')
    sendAjaxRequest('put', '/api/cards/', {name: name}, cardAddedHandler);

  event.preventDefault();
}

function itemUpdatedHandler() {
  let item = JSON.parse(this.responseText);
  let element = document.querySelector('li.item[data-id="' + item.id + '"]');
  let input = element.querySelector('input[type=checkbox]');
  element.checked = item.done == "true";
}

function itemAddedHandler() {
  if (this.status != 200) window.location = '/';
  let item = JSON.parse(this.responseText);

  // Create the new item
  let new_item = createItem(item);

  // Insert the new item
  let card = document.querySelector('article.card[data-id="' + item.card_id + '"]');
  let form = card.querySelector('form.new_item');
  form.previousElementSibling.append(new_item);

  // Reset the new item form
  form.querySelector('[type=text]').value="";
}

function itemDeletedHandler() {
  if (this.status != 200) window.location = '/';
  let item = JSON.parse(this.responseText);
  let element = document.querySelector('li.item[data-id="' + item.id + '"]');
  element.remove();
}

function cardDeletedHandler() {
  if (this.status != 200) window.location = '/';
  let card = JSON.parse(this.responseText);
  let article = document.querySelector('article.card[data-id="'+ card.id + '"]');
  article.remove();
}

function cardAddedHandler() {
  if (this.status != 200) window.location = '/';
  let card = JSON.parse(this.responseText);

  // Create the new card
  let new_card = createCard(card);

  // Reset the new card input
  let form = document.querySelector('article.card form.new_card');
  form.querySelector('[type=text]').value="";

  // Insert the new card
  let article = form.parentElement;
  let section = article.parentElement;
  section.insertBefore(new_card, article);

  // Focus on adding an item to the new card
  new_card.querySelector('[type=text]').focus();
}

function createCard(card) {
  let new_card = document.createElement('article');
  new_card.classList.add('card');
  new_card.setAttribute('data-id', card.id);
  new_card.innerHTML = `

  <header>
    <h2><a href="cards/${card.id}">${card.name}</a></h2>
    <a href="#" class="delete">&#10761;</a>
  </header>
  <ul></ul>
  <form class="new_item">
    <input name="description" type="text">
  </form>`;

  let creator = new_card.querySelector('form.new_item');
  creator.addEventListener('submit', sendCreateItemRequest);

  let deleter = new_card.querySelector('header a.delete');
  deleter.addEventListener('click', sendDeleteCardRequest);

  return new_card;
}

function createItem(item) {
  let new_item = document.createElement('li');
  new_item.classList.add('item');
  new_item.setAttribute('data-id', item.id);
  new_item.innerHTML = `
  <label>
    <input type="checkbox"> <span>${item.description}</span><a href="#" class="delete">&#10761;</a>
  </label>
  `;

  new_item.querySelector('input').addEventListener('change', sendItemUpdateRequest);
  new_item.querySelector('a.delete').addEventListener('click', sendDeleteItemRequest);

  return new_item;
}


addEventListeners();
