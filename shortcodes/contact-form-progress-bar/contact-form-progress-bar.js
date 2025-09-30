document.addEventListener("DOMContentLoaded", function () {
  // Initialize all radio dropdowns
  initRadioDropdowns();
});

function initRadioDropdowns() {
  const dropdowns = document.querySelectorAll(".budi-cfpb-radio-dropdowns");

  dropdowns.forEach((dropdown) => {
    const label = dropdown.querySelector("label");
    const radioItems = dropdown.querySelector(".budi-cfpb-radio-items");
    const radioInputs = dropdown.querySelectorAll('input[type="radio"]');

    if (!label || !radioItems) return;

    // Add proper ARIA attributes to radio items
    radioItems.setAttribute("role", "listbox");
    radioInputs.forEach((input, index) => {
      const listItem = input.closest(".wpcf7-list-item");
      if (listItem) {
        listItem.setAttribute("role", "option");
        listItem.setAttribute("aria-selected", "false");
        input.setAttribute("tabindex", "-1");
      }
    });

    // Add click handler to label to toggle dropdown
    label.addEventListener("click", function (e) {
      e.preventDefault();
      toggleDropdown(dropdown);
    });

    // Add click handlers to radio inputs
    radioInputs.forEach((input) => {
      input.addEventListener("change", function () {
        updateLabelText(dropdown, this);
        closeDropdown(dropdown);
        updateAriaAttributes(dropdown, this);
      });
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", function (e) {
      if (!dropdown.contains(e.target)) {
        closeDropdown(dropdown);
      }
    });

    // Handle keyboard navigation for the dropdown container
    dropdown.addEventListener("keydown", function (e) {
      handleDropdownKeydown(e, dropdown);
    });

    // Handle keyboard navigation for the label
    label.addEventListener("keydown", function (e) {
      if (e.key === "Enter" || e.key === " " || e.key === "ArrowDown") {
        e.preventDefault();
        toggleDropdown(dropdown);
      }
    });

    // Initialize with selected value if any
    const checkedInput = dropdown.querySelector('input[type="radio"]:checked');
    if (checkedInput) {
      updateLabelText(dropdown, checkedInput);
      updateAriaAttributes(dropdown, checkedInput);
    }
  });
}

function toggleDropdown(dropdown) {
  const isOpen = dropdown.classList.contains("open");

  if (isOpen) {
    closeDropdown(dropdown);
  } else {
    openDropdown(dropdown);
  }
}

function openDropdown(dropdown) {
  // Close all other dropdowns first
  document
    .querySelectorAll(".budi-cfpb-radio-dropdowns.open")
    .forEach((openDropdown) => {
      if (openDropdown !== dropdown) {
        closeDropdown(openDropdown);
      }
    });

  dropdown.classList.add("open");
  dropdown.setAttribute("aria-expanded", "true");
  dropdown.querySelector("label").setAttribute("aria-expanded", "true");

  // Focus the first radio input for keyboard navigation
  const firstRadio = dropdown.querySelector('input[type="radio"]');
  if (firstRadio) {
    firstRadio.focus();
  }
}

function closeDropdown(dropdown) {
  dropdown.classList.remove("open");
  dropdown.setAttribute("aria-expanded", "false");
  dropdown.querySelector("label").setAttribute("aria-expanded", "false");
}

function updateLabelText(dropdown, selectedInput) {
  const label = dropdown.querySelector("label");
  const selectedLabel = selectedInput.nextElementSibling;

  if (label && selectedLabel) {
    // Get the original question text from the label's for attribute or data attribute
    const originalText =
      label.getAttribute("data-original-text") || label.textContent;

    // Store original text if not already stored
    if (!label.getAttribute("data-original-text")) {
      label.setAttribute("data-original-text", originalText);
    }

    // Update label text to show selected option
    label.textContent = selectedLabel.textContent;

    // Add class to indicate selection
    dropdown.classList.add("has-selection");
  }
}

function updateAriaAttributes(dropdown, selectedInput) {
  const radioInputs = dropdown.querySelectorAll('input[type="radio"]');
  const listItems = dropdown.querySelectorAll(".wpcf7-list-item");

  // Update aria-selected for all options
  listItems.forEach((item, index) => {
    const input = radioInputs[index];
    if (input === selectedInput) {
      item.setAttribute("aria-selected", "true");
    } else {
      item.setAttribute("aria-selected", "false");
    }
  });
}

function handleDropdownKeydown(e, dropdown) {
  const isOpen = dropdown.classList.contains("open");
  const radioInputs = dropdown.querySelectorAll('input[type="radio"]');
  const listItems = dropdown.querySelectorAll(".wpcf7-list-item");
  const currentFocusedIndex = Array.from(radioInputs).findIndex(
    (input) => input === document.activeElement,
  );

  switch (e.key) {
    case "Enter":
    case " ":
      e.preventDefault();
      if (isOpen && currentFocusedIndex >= 0) {
        // Select the currently focused option
        radioInputs[currentFocusedIndex].click();
      } else {
        // Toggle dropdown
        toggleDropdown(dropdown);
      }
      break;

    case "ArrowDown":
      e.preventDefault();
      if (!isOpen) {
        openDropdown(dropdown);
      } else {
        // Navigate to next option
        const nextIndex =
          currentFocusedIndex < radioInputs.length - 1
            ? currentFocusedIndex + 1
            : 0;
        radioInputs[nextIndex].focus();
      }
      break;

    case "ArrowUp":
      e.preventDefault();
      if (isOpen) {
        // Navigate to previous option
        const prevIndex =
          currentFocusedIndex > 0
            ? currentFocusedIndex - 1
            : radioInputs.length - 1;
        radioInputs[prevIndex].focus();
      }
      break;

    case "Escape":
      e.preventDefault();
      if (isOpen) {
        closeDropdown(dropdown);
        // Return focus to the label
        dropdown.querySelector("label").focus();
      }
      break;

    case "Tab":
      // Allow normal tab behavior, but close dropdown if open
      if (isOpen) {
        closeDropdown(dropdown);
      }
      break;
  }
}

// Handle form reset
document.addEventListener("wpcf7beforesubmit", function (event) {
  console.log("wpcf7beforesubmit");
  const dropdowns = event.target.querySelectorAll(".budi-cfpb-radio-dropdowns");
  dropdowns.forEach((dropdown) => {
    const label = dropdown.querySelector("label");
    const originalText = label.getAttribute("data-original-text");
    if (originalText) {
      label.textContent = originalText;
      dropdown.classList.remove("has-selection");
    }

    // Reset ARIA attributes
    const listItems = dropdown.querySelectorAll(".wpcf7-list-item");
    listItems.forEach((item) => {
      item.setAttribute("aria-selected", "false");
    });
  });
});

// Contact Form 7 Success Handler
jQuery(document).ready(function ($) {
  // Listen for Contact Form 7 submission success
  $(document).on("wpcf7mailsent", function (event) {
    const formId = event.detail.contactFormId;

    // Find the progress bar wrapper that contains this form
    const progressBarWrapper = $(
      `.budi-contact-form-progress-bar__wrapper[data-contact-form-id="${formId}"]`,
    );

    if (progressBarWrapper.length) {
      // Hide the form
      const formElement = progressBarWrapper.find(
        ".budi-contact-form-progress-bar__form .wpcf7",
      );
      if (formElement.length) {
        formElement.hide();
      }

      // Show the thank you block
      const thankYouBlock = progressBarWrapper.find(
        ".budi-contact-form-progress-bar__thank-you-wrapper",
      );
      if (thankYouBlock.length) {
        thankYouBlock.fadeIn(300);
      }
    }
  });

  // Listen for Contact Form 7 validation errors to reset form state if needed
  $(document).on("wpcf7invalid", function (event) {
    console.log("wpcf7invalid");
    const formId = event.detail.contactFormId;

    // Find the progress bar wrapper that contains this form
    const progressBarWrapper = $(
      `.budi-contact-form-progress-bar__wrapper[data-contact-form-id="${formId}"]`,
    );

    if (progressBarWrapper.length) {
      // Ensure form is visible and thank you is hidden
      const formElement = progressBarWrapper.find(
        ".budi-contact-form-progress-bar__form .wpcf7",
      );
      if (formElement.length) {
        formElement.fadeIn(300);
      }

      const thankYouBlock = progressBarWrapper.find(
        ".budi-contact-form-progress-bar__thank-you-wrapper",
      );
      if (thankYouBlock.length) {
        thankYouBlock.hide().removeClass("show");
      }
    }
  });

  // Listen for form submission start to show loading state if needed
  $(document).on("wpcf7beforesubmit", function (event) {
    console.log("wpcf7beforesubmit");
    const formId = event.detail.contactFormId;

    // Find the progress bar wrapper that contains this form
    const progressBarWrapper = $(
      `.budi-contact-form-progress-bar__wrapper[data-contact-form-id="${formId}"]`,
    );

    if (progressBarWrapper.length) {
      // Ensure form is visible and thank you is hidden during submission
      const formElement = progressBarWrapper.find(
        ".budi-contact-form-progress-bar__form .wpcf7",
      );
      if (formElement.length) {
        formElement.fadeIn(300);
      }

      const thankYouBlock = progressBarWrapper.find(
        ".budi-contact-form-progress-bar__thank-you-wrapper",
      );
      if (thankYouBlock.length) {
        thankYouBlock.hide().removeClass("show");
      }
    }
  });

  // Listen for form submission failure to reset form state
  $(document).on("wpcf7mailfailed", function (event) {
    console.log("wpcf7mailfailed");
    const formId = event.detail.contactFormId;

    // Find the progress bar wrapper that contains this form
    const progressBarWrapper = $(
      `.budi-contact-form-progress-bar__wrapper[data-contact-form-id="${formId}"]`,
    );

    if (progressBarWrapper.length) {
      // Ensure form is visible and thank you is hidden on failure
      const formElement = progressBarWrapper.find(
        ".budi-contact-form-progress-bar__form .wpcf7",
      );
      if (formElement.length) {
        formElement.fadeIn(300);
      }

      const thankYouBlock = progressBarWrapper.find(
        ".budi-contact-form-progress-bar__thank-you-wrapper",
      );
      if (thankYouBlock.length) {
        thankYouBlock.hide().removeClass("show");
      }
    }
  });

  // after submit
  $(document).on("wpcf7submit", function (event) {
    console.log("wpcf7submit");
    const formId = event.detail.contactFormId;

    // Find the progress bar wrapper that contains this form
    const progressBarWrapper = $(
      `.budi-contact-form-progress-bar__wrapper[data-contact-form-id="${formId}"]`,
    );
  });

  $(document).on("click", "#cf7mls-next-btn-cf7mls_step-1", function (e) {
    e.preventDefault();
    // When step 1 next button is clicked, check each dropdown for not valid tip
    setTimeout(function () {
      $(".budi-cfpb-radio-dropdowns").each(function () {
        var $dropdown = $(this);
        // Remove any previously added custom not valid tips to prevent duplicates
        $dropdown.find(".budi-custom-not-valid-tip").remove();

        var $invalidElement = $dropdown.find(".wpcf7-not-valid-tip").first();
        if ($invalidElement.length) {
          var invalidText = $invalidElement.text();
          var $customTip = $('<div class="wpcf7-not-valid-tip budi-custom-not-valid-tip"></div>').text(invalidText);
          $dropdown.append($customTip);
          console.log("Not valid tip found in dropdown");
        }
      });
    }, 800);
  });

  $(document).on("click", '.cf7mls_next', function() {
    var $parentElement = $(this).parents('.budi-contact-form-progress-bar__wrapper');
    setTimeout(function() {
      // Use jQuery's animate to scroll to the parent element
      $('html, body').animate({
        scrollTop: $parentElement.offset().top
      }, 500); // 500ms for smooth scroll
    }, 800);
  });
});
