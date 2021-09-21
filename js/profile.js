if (localStorage.getItem("user")) {
  const data = JSON.parse(localStorage.getItem("user"));
  $("#userNameM").html(data.user.first_name + " " + data.user.last_name);
  $("#userImageM").attr("src", data.user.profileImage.substring(1));

  $("#firstName").attr("value", data.user.first_name);
  $("#lastName").attr("value", data.user.last_name);
  $("#city").attr("value", data.user.city);
  $("option[value=" + data.user.country + "]").attr("selected", "selected");
  $("option[value=" + data.user.birthday.split("-")[0] + "]").attr(
    "selected",
    "selected"
  );
  $("option[value=" + data.user.birthday.split("-")[1] + "]").attr(
    "selected",
    "selected"
  );
  $("option[value=" + data.user.birthday.split("-")[2] + "]").attr(
    "selected",
    "selected"
  );
}
