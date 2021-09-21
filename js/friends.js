// Load notifications as the user enters the page
$(window).on("load", function () {
  async function fetchUserFriends() {
    const data = new URLSearchParams();
    data.append("action", "get");
    const response = await fetch("php/API/friend.php?action=get");
    if (!response.ok) {
      const message = `An error has occured: ${response.status}`;
      throw new Error(message);
    }

    const results = await response.json();

    return results;
  }

  fetchUserFriends()
    .then((result) => {
      console.log(result);
      appendFriends(result.friends);
      appendPendings(result.pendings);
      appendBlocked(result.blocked);
      hidePreloader();
    })
    .catch((err) => {
      console.log(err);
    });
});

let count = 0;

function appendFriends(result) {
  const friendsContainer = document.getElementById("userFriends");
  let haveFriends = false;
  for (const [id, data] of Object.entries(result)) {
    haveFriends = true;
    const notification = `<div class="row" id="friend_${id}">
    <div class="col-md-2 col-sm-2">
      <img
        src="${data.profileImage.substring(1)}"
        alt="user"
        class="profile-photo-lg"
      />
    </div>
    <div class="col-md-7 col-sm-7">
      <h5><a href="#" class="profile-link">${data.first_name} ${
      data.last_name
    }</a></h5>
      <p>${data.birthday}</p>
      <p class="text-muted">${data.city}, ${data.country}</p>
    </div>
    <div class="col-md-3 col-sm-3">
      <button class="btn btn-danger mb-5 removeFriend" id="${id}">Remove Friend</button>
      <button class="btn btn-warning mb-5 blockFriend" id="${
        data.id
      }">Block</button>
    </div>
  </div>`;

    friendsContainer.innerHTML += notification;
  }
  if (haveFriends) {
    $(".removeFriend").click(removeUserFriend);
    $(".blockFriend").click(blockUserFriend);
  } else {
    friendsContainer.innerHTML = `<h6 class="text-muted"> No friends Yet, Go to <a href="find.html"> Connect With Friend page </a> <h6>`;
  }
}

function appendPendings(result) {
  const pendingContainer = document.getElementById("userPendings");
  let havePendings = false;
  for (const [id, data] of Object.entries(result)) {
    havePendings = true;
    const pending = `<div class="row" id="pending_${id}">
    <div class="col-md-2 col-sm-2">
      <img
        src="${data.profileImage.substring(1)}"
        alt="user"
        class="profile-photo-lg"
      />
    </div>
    <div class="col-md-7 col-sm-7">
      <h5><a href="#" class="profile-link">${data.first_name} ${
      data.last_name
    }</a></h5>
      <p>2021-1-1</p>
      <p class="text-muted">${data.city}, ${data.country}</p>
    </div>
    <div class="col-md-3 col-sm-3">
      <button class="btn btn-danger removeRequest mb-5" id="${id}">Remove Request</button>
      <button class="btn btn-warning blockRequest mb-5" id="${id}">Block</button>
      <input type="hidden" id="fromUser_${id}" value="${data.id}" />
    </div>
  </div>`;
    pendingContainer.innerHTML += pending;
  }

  if (havePendings) {
    $(".removeRequest").click(removePendingRequest);
    $(".blockRequest").click(blockPendingRequest);
  } else {
    pendingContainer.innerHTML = `<h6 class="text-muted"> No pending Requests </h6>`;
  }
}

function appendBlocked(result) {
  const blockedContainer = document.getElementById("userBlocked");
  let haveBlocked = false;
  for (const [id, data] of Object.entries(result)) {
    haveBlocked = true;
    const blocked = `<div class="row" id="blocked_${id}">
    <div class="col-md-2 col-sm-2">
      <img
        src="${data.profileImage.substring(1)}"
        alt="user"
        class="profile-photo-lg"
      />
    </div>
    <div class="col-md-7 col-sm-7">
      <h5><a href="#" class="profile-link">${data.first_name} ${
      data.last_name
    }</a></h5>
      <p>2021-1-1</p>
      <p class="text-muted">${data.city}, ${data.country}</p>
    </div>
    <div class="col-md-3 col-sm-3">
      <button class="btn btn-danger removeBlock mb-5" id="${id}">Remove Block</button>
    </div>
  </div>`;
    blockedContainer.innerHTML += blocked;
  }

  if (haveBlocked) {
    $(".removeBlock").click(removeBlock);
  } else {
    blockedContainer.innerHTML = `<h6 class="text-muted"> No Blocked Users </h6>`;
  }
}

async function friendAPI(action, id, fromUserId) {
  const data = new URLSearchParams();
  data.append("action", "get");
  const response = await fetch(
    "php/API/friend.php?action=" + action + "&id=" + id + "&from=" + fromUserId
  );
  if (!response.ok) {
    const message = `An error has occured: ${response.status}`;
    throw new Error(message);
  }

  const results = await response.json();

  return results;
}

function removePendingRequest(event) {
  const action = "removePending";
  const notificationId = event.currentTarget.id;
  friendAPI(action, notificationId).then((result) => {
    if (result.ok == 200) {
      removePending(notificationId);
    }
  });
}

function blockPendingRequest(event) {
  const action = "blockPending";
  const notificationId = event.currentTarget.id;
  friendAPI(
    action,
    notificationId,
    $("#fromUser_" + notificationId).val()
  ).then((result) => {
    if (result.ok == 200) {
      removePending(notificationId);
    }
  });
}

function removeUserFriend(event) {
  const action = "remove";
  const userFriendId = event.currentTarget.id;

  friendAPI(action, userFriendId).then((result) => {
    if (result.ok == 200) {
      removeFriend(userFriendId);
    }
  });
}

function blockUserFriend(event) {
  const action = "blockPending";
  const userFriendId = event.currentTarget.id;
  friendAPI(action, userFriendId).then((result) => {
    if (result.ok == 200) {
      removeFriend(event.currentTarget.previousElementSibling.id);
    }
  });
}

function removeBlock(event) {
  const action = "removeBlock";
  const userFriendId = event.currentTarget.id;

  friendAPI(action, userFriendId).then((result) => {
    if (result.ok == 200) {
      removeBlockRow(userFriendId);
    }
  });
}

function removeFriend(id) {
  $("#friend_" + id).fadeOut(100, function () {
    $(this).remove();
  });
}

function removePending(id) {
  $("#pending_" + id).fadeOut(100, function () {
    $(this).remove();
  });
}

function removeBlockRow(id) {
  $("#blocked_" + id).fadeOut(100, function () {
    $(this).remove();
  });
}

function showBlocked() {
  $("#userBlocked").removeClass("hidden");
  $("#showBlockedA").addClass("hidden");
  $("#hideBlockedA").removeClass("hidden");
}

function hideBlocked() {
  $("#userBlocked").addClass("hidden");
  $("#showBlockedA").removeClass("hidden");
  $("#hideBlockedA").addClass("hidden");
}
