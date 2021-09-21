let count = 0;
let resultsCount = 0;
hidePreloader();
$("#searchBox").keyup((event) => {
  if ($("#searchBox").val().length % 3 == 0 && $("#searchBox").val() != "") {
    searchQuery($("#searchBox").val()).then((result) => {
      appendSearchResult(result);
    });
  } else if ($("#searchBox").val() == "") {
    count = 0;
  }
});

async function searchQuery(query) {
  const response = await fetch(
    "php/API/find.php?action=querySearch&search=" + query
  );

  if (!response.ok) {
    const message = `An error has occured: ${response.status}`;
    throw new Error(message);
  }

  const results = await response.json();

  return results;
}

async function findAPI(action, id, date) {
  const response = await fetch(
    "php/API/find.php?action=" + action + "&id=" + id + "&date=" + date
  );

  if (!response.ok) {
    const message = `An error has occured: ${response.status}`;
    throw new Error(message);
  }

  const results = await response.json();

  return results;
}

function appendSearchResult(result) {
  resultsCount = 0;
  const searchContainer = document.getElementById("searchResult");
  searchContainer.innerHTML = "";
  for (const [key, value] of Object.entries(result)) {
    const userCard = `
        <div class="nearby-user" id="user_${key}">
                <div class="row">
                  <div class="col-md-2 col-sm-2">
                    <img
                      src="${value.profile_image.substring(1)}"
                      alt="user"
                      class="profile-photo-lg"
                    />
                  </div>
                  <div class="col-md-7 col-sm-7">
                    <h5><a href="#" class="profile-link">${value.first_name} ${
      value.last_name
    }</a></h5>
                    <p class="text-muted">${value.city}, ${value.country}</p>
                  </div>
                  <div class="col-md-3 col-sm-3">
                    <button class="btn btn-primary mb-5 addButton" id="${key}">Add Friend</button>
                    <button class="btn btn-warning mb-5 blockButton" id="${key}">Block</button>
                  </div>
                </div>
              </div>`;

    searchContainer.innerHTML += userCard;
    resultsCount++;
  }
  updateHeader(resultsCount);
  $(".addButton").click(sendRequest);
  $(".blockButton").click(blockUser);
}

function sendRequest(event) {
  const id = event.currentTarget.id;
  const action = "createRequest";

  findAPI(action, id).then((result) => {
    if (result.ok == 200) {
      removeUserCard(id);
      resultsCount--;
      updateHeader(resultsCount);
    }
  });
}

function blockUser(event) {
  const id = event.currentTarget.id;
  const action = "block";

  findAPI(action, id).then((result) => {
    if (result.ok == 200) {
      removeUserCard(id);
      resultsCount--;
      updateHeader(resultsCount);
    }
  });
}

function removeUserCard(id) {
  $("#user_" + id).fadeOut(100, function () {
    $(this).remove();
  });
}

function updateHeader(count) {
  if (count > 0) {
    $("#searchHeader").html("Found " + count + " Results");
  } else {
    $("#searchHeader").html("Search for friends");
  }
}
