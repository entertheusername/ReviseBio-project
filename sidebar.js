// opening and closing the sidepanel
function openNav(elementID) {
  // Check the screen width
  if (window.innerWidth <= 758) {
    // For mobile screens (width 758px or less)
    document.getElementById(elementID).style.width = "70%";
  } else {
    // For larger screens
    document.getElementById(elementID).style.width = "30%";
  }
}

function closeNav(elementID) {
  document.getElementById(elementID).style.width = "0";
}
// popup for middle (for general purpose)
myButton.addEventListener(
  "click",
  function () {
    myPopup.classList.add("show");
  }
);
closePopup.addEventListener(
  "click",
  function () {
    myPopup.classList.remove(
      "show"
    );
  }
);
window.addEventListener(
  "click",
  function (event) {
    if (event.target == myPopup) {
      myPopup.classList.remove(
        "show"
      );
    }
  }
);

// popup for side bar (for changing the username etc etc)
function showPopup(popupId) {
  // Show the popup by its ID
  const popup = document.getElementById(popupId);
  if (popup) {
    popup.style.display = 'block';
  }
}

function closePopup(popupId) {
  // Hide the popup by its ID
  const popup = document.getElementById(popupId);
  if (popup) {
    popup.style.display = 'none';
  }
}