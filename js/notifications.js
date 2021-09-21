// Load notifications as the user enters the page
$(window).on("load", function () {
  async function fetchUserNotifications() {
    const data = new URLSearchParams();
    data.append("action", "get");
    const response = await fetch("php/API/notification.php?action=get");
    if (!response.ok) {
      const message = `An error has occured: ${response.status}`;
      throw new Error(message);
    }

    const results = await response.json();

    return results;
  }

  fetchUserNotifications()
    .then((result) => {
      appendNotifications(result);
      hidePreloader();
    })
    .catch((err) => {
      console.log(err);
    });
});

let count = 0;
const notificationsContainer = document.getElementById("notifications");

function appendNotifications(result) {
  for (const [id, data] of Object.entries(result)) {
    const notification = `<li id="notification_${id}">
    <a>
      <div class="contact">
        <img
          src="${data.from_user_image.substring(1)}"
          alt=""
          class="profile-photo-sm pull-left"
        />
        <div class="msg-preview">
          <h6>${data.from_user_first_name} ${data.from_user_last_name}</h6>
          <p class="text-muted">${data.body}</p>
          <small class="text-muted">${data.date}</small>
        </div>

        <button class="btn btn-primary acceptButton" id="${id}">Accept</button>
        <button class="btn btn-danger declineButton" id="${id}">Decline</button>
        <button class="btn btn-warning blockButton" id="${id}">Block</button>
        <input type="hidden" id="fromUserId_${id}" value="${
      data.from_user_id
    }" />
      </div>
    </a>
  </li>`;
    count++;
    notificationsContainer.innerHTML += notification;
  }
  updateHeader();
  $(".acceptButton").click(acceptUser);
  $(".declineButton").click(declineUser);
  $(".blockButton").click(blockUser);
}

async function notificationAPI(action, id, fromUserId) {
  const data = new URLSearchParams();
  data.append("action", "get");
  const response = await fetch(
    "php/API/notification.php?action=" +
      action +
      "&id=" +
      id +
      "&from=" +
      fromUserId
  );
  if (!response.ok) {
    const message = `An error has occured: ${response.status}`;
    throw new Error(message);
  }

  const results = await response.json();

  return results;
}

function acceptUser(event) {
  const action = "accept";
  const notificationId = event.currentTarget.id;

  notificationAPI(
    action,
    notificationId,
    $("#fromUserId_" + notificationId).val()
  ).then((result) => {
    if (result.ok == 200) {
      removeNotification(notificationId);
    }
  });
}

function declineUser(event) {
  const action = "decline";
  const notificationId = event.currentTarget.id;

  notificationAPI(action, notificationId).then((result) => {
    if (result.ok == 200) {
      removeNotification(notificationId);
    }
  });
}

function blockUser(event) {
  const action = "block";
  const notificationId = event.currentTarget.id;

  notificationAPI(
    action,
    notificationId,
    $("#fromUserId_" + notificationId).val()
  ).then((result) => {
    if (result.ok == 200) {
      removeNotification(notificationId);
    }
  });
}

function removeNotification(id) {
  $("#notification_" + id).fadeOut(100, function () {
    $(this).remove();
  });
  count--;
  updateHeader();
}

function updateHeader() {
  const header = document.getElementById("headerCount");
  header.innerText =
    count > 0
      ? "You have " + count + " notifications"
      : "You don't have any notification";
}
