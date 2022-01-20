function addEventListeners() {
    let likeToggles = document.querySelectorAll('div.like');
    [].forEach.call(likeToggles, function(like) {
        like.addEventListener('click', toggleLike);
    });

    let tagSearchButton = document.querySelector("button.tag_search_button");
    if (tagSearchButton != null)
        tagSearchButton.addEventListener('click', searchUserTag);

    let inviteSearch = document.querySelector("button.invite_search_button");
    if(inviteSearch != null)
        inviteSearch.addEventListener('click', searchUserInvite);

    let banToggles = document.querySelectorAll('button.ban');
    [].forEach.call(banToggles, function (banToggle) {
       banToggle.addEventListener('click', toggleBan);
    });

    let acceptFriendRequests = document.querySelectorAll('button.accept_friend');
    [].forEach.call(acceptFriendRequests, function (acceptButton) {
        acceptButton.addEventListener('click', acceptFriendRequest);
    });

    let denyFriendRequests = document.querySelectorAll('button.deny_friend');
    [].forEach.call(denyFriendRequests, function (denyButton) {
        denyButton.addEventListener('click', denyFriendRequest);
    });

    let sendFriendRequest = document.querySelectorAll('button.send_request');
    [].forEach.call(sendFriendRequest, function (sendButton) {
        sendButton.addEventListener('click', sendRequest);
    });

    let removeFriendship = document.querySelectorAll('button.remove_friend');
    [].forEach.call(removeFriendship, function (removeButton) {
        removeButton.addEventListener('click', removeFriend);
    });

    let acceptInvites = document.querySelectorAll('button.accept_invite');
    [].forEach.call(acceptInvites, function (acceptButton) {
        acceptButton.addEventListener('click', acceptInvite);
    });

    let denyInvites = document.querySelectorAll('button.deny_invite');
    [].forEach.call(denyInvites, function (denyButton) {
        denyButton.addEventListener('click', denyInvite);
    });

    let acceptRequests = document.querySelectorAll('button.accept_member');
    [].forEach.call(acceptRequests, function (acceptButton) {
        acceptButton.addEventListener('click', acceptRequest);
    });

    let denyRequests = document.querySelectorAll('button.deny_member');
    [].forEach.call(denyRequests, function (denyButton) {
        denyButton.addEventListener('click', denyRequest);
    });
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

function searchUserTag() {
    let searchBoxes = document.getElementsByClassName('tag_search_field');
    let searchValue = searchBoxes[searchBoxes.length - 1].value;

    sendAjaxRequest('post', '/api/search/users', {searchString: searchValue}, searchHandlerTag);
}

function searchUserInvite() {
    let searchBoxes = document.getElementsByClassName('invite_search_field');
    let searchValue = searchBoxes[searchBoxes.length - 1].value;

    sendAjaxRequest('post', '/api/search/users', {searchString: searchValue}, searchHandlerInvite);
}

function searchHandlerTag() {
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

function searchHandlerInvite() {
    let users = JSON.parse(this.responseText);

    let inviteLabel = document.getElementById('invite_label');
    let group_id = inviteLabel.getAttribute('group_id');

    let searchResults = document.createElement('table');

    searchResults.setAttribute('id', 'invite_search_results');
    searchResults.classList.add('search_results_table');

    users.forEach(function (user) {
        let row = document.createElement('tr');
        row.classList.add('search_results_row');

        let anchor = document.createElement('a');
        anchor.innerHTML = user.name;
        anchor.setAttribute('href', '/users/' + user.id);
        anchor.classList.add('search_results_row_name');

        let addInviteButton = document.createElement('button');
        addInviteButton.setAttribute('class', 'btn btn-primary');
        addInviteButton.setAttribute('type', 'button');
        addInviteButton.classList.add('search_results_row_tag');
        addInviteButton.innerHTML = 'Invite';
        addInviteButton.setAttribute('user_id', user.id);
        addInviteButton.setAttribute('group_id', group_id);
        addInviteButton.addEventListener('click', addInvite);

        row.appendChild(anchor);
        row.appendChild(addInviteButton);
        searchResults.appendChild(row);
    })

    let previousResults = document.getElementById('invite_search_results');

    if (previousResults != null) inviteLabel.removeChild(previousResults);

    inviteLabel.appendChild(searchResults);
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


function addInvite() {
    let user_id = this.getAttribute('user_id');
    let group_id = this.getAttribute('group_id');

    sendAjaxRequest('post', '/api/create_request', {user_id: user_id, group_id: group_id}, null);

    this.style.backgroundColor = 'grey';
    this.removeEventListener('click', addInvite);
}

function toggleBan() {
    let user_id = this.getAttribute('user_id');
    let admin_id = this.getAttribute('admin_id');
    let banned = this.innerHTML === 'Unban';

    console.log(banned, admin_id, user_id);

    if(!banned) {
        sendAjaxRequest('post', '/api/users/ban', {user_id: user_id, admin_id: admin_id}, null);
        this.innerHTML = 'Unban';
    }
    else {
        sendAjaxRequest('post', '/api/users/unban', {user_id: user_id, admin_id: admin_id}, null);
        this.innerHTML = 'Ban';
    }
}

function acceptFriendRequest() {
    let target_id = this.getAttribute('target_id');
    let sender_id = this.getAttribute('sender_id');
    let req_not_id = this.getAttribute('req_not_id');

    console.log(target_id, sender_id);

    sendAjaxRequest('post', '/api/accept_friend_request', {target_id : target_id, sender_id : sender_id, req_not_id: req_not_id}, null);

    this.style.backgroundColor = "gray";
}

function denyFriendRequest() {
    let target_id = this.getAttribute('target_id');
    let sender_id = this.getAttribute('sender_id');
    let req_not_id = this.getAttribute('req_not_id');

    console.log(target_id, sender_id);

    sendAjaxRequest('post', '/api/accept_friend_request', {target_id : target_id, sender_id : sender_id, req_not_id: req_not_id}, null);

    this.style.backgroundColor = "gray";
}

function sendRequest() {
  let target_id = this.getAttribute('target_id');
  let sender_id = this.getAttribute('sender_id');

  console.log(target_id, sender_id);

  sendAjaxRequest('post', '/api/send_request', {target_id : target_id, sender_id : sender_id}, null);

  this.style.backgroundColor = "gray";
}

function removeFriend() {
  let target_id = this.getAttribute('target_id');
  let sender_id = this.getAttribute('sender_id');

  console.log(target_id, sender_id);

  sendAjaxRequest('post', '/api/remove_friend', {target_id : target_id, sender_id : sender_id}, null);

  this.style.backgroundColor = "gray";
}

function acceptInvite() {
    let group_id = this.getAttribute('group_id');
    let user_id = this.getAttribute('user_id');
    let inv_not_id = this.getAttribute('inv_not_id');

    console.log(group_id, user_id, inv_not_id);

    sendAjaxRequest('post', '/api/accept_invite', {group_id : group_id, user_id : user_id, inv_not_id: inv_not_id}, null);

    this.style.backgroundColor = "gray";
}

function denyInvite() {
    let group_id = this.getAttribute('group_id');
    let user_id = this.getAttribute('user_id');
    let inv_not_id = this.getAttribute('inv_not_id');

    sendAjaxRequest('post', '/api/deny_invite', {group_id : group_id, user_id : user_id, inv_not_id: inv_not_id}, null);

    this.style.backgroundColor = "gray";
}

function acceptRequest() {
    let group_id = this.getAttribute('group_id');
    let user_id = this.getAttribute('user_id');
    let req_not_id = this.getAttribute('req_not_id');

    console.log(group_id, user_id, req_not_id);

    sendAjaxRequest('post', '/api/accept_request', {group_id : group_id, user_id : user_id, req_not_id: req_not_id}, null);

    this.style.backgroundColor = "gray";
}

function denyRequest() {
    let group_id = this.getAttribute('group_id');
    let user_id = this.getAttribute('user_id');
    let req_not_id = this.getAttribute('req_not_id');

    console.log(group_id, user_id, req_not_id);

    sendAjaxRequest('post', '/api/deny_request', {group_id : group_id, user_id : user_id, req_not_id: req_not_id}, null);

    this.style.backgroundColor = "gray";
}

function dropdownPostFunction() {
  document.getElementById("myDropdown-notifs-post").classList.toggle("show");
}

function dropdownFriendshipFunction() {
  document.getElementById("myDropdown-notifs-friendship").classList.toggle("show");
}

function dropdownGroupsFunction() {
  document.getElementById("myDropdown-notifs-groups").classList.toggle("show");
}

// Close the dropdown menu if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn-notifs')) {
    var dropdowns = document.getElementsByClassName("dropdown-content-notifs");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}

addEventListeners();
