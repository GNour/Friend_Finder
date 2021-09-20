// Login Functions

async function loginUser(data) {
  console.log(data);
  const response = await fetch("php/API/login.php", {
    method: "POST",
    headers: {},
    body: data,
  });
  if (!response.ok) {
    const message = `An error has occured: ${response.status}`;
    throw new Error(message);
  }

  const results = await response.json();

  return results;
}

$("#loginButton").click((event) => {
  const loginForm = document.getElementById("loginForm");
  const data = new URLSearchParams();
  for (const pair of new FormData(loginForm)) {
    data.append(pair[0], pair[1]);
  }
  loginUser(data).then((result) => {
    if (result.ok == 200) {
      window.location.href = "./notifications.html";
    } else {
    }
  });
});