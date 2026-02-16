document.addEventListener("DOMContentLoaded", function () {
  const btnNext = document.querySelector(".arrow-next");
  const btnPrev = document.querySelector(".arrow-prev");
  const allImages = document.querySelectorAll(".images-wrapper--large img");

  let smallImages = document.querySelectorAll(
    ".images-wrapper--container-small img",
  );
  const smallWrapper = document.querySelector(
    ".images-wrapper--container-small",
  );

  const sectionParent = document.querySelector(".section-gallery");
  const imageModal = document.querySelector(".image-modal");
  const closeModal = document.querySelector(".image-modal-modal-wrapper");
  const openImage = document.querySelector(".images-wrapper--open-image");
  const body = document.querySelector("body");

  const openImageCounter = document.querySelector(
    ".images-wrapper--open-image p:first-child",
  );
  const openImageTitle = document.querySelector(
    ".images-wrapper--open-image p:nth-child(2)",
  );

  const largeImageWrapper = allImages[0].parentElement;

  const totalSlidesCount = allImages.length;
  const realSlidesCount = totalSlidesCount - 2;

  let currSlide = 1;

  const TRANSITION_DURATION = 500;

  const THUMBNAIL_WIDTH = 189;
  const GAP_WIDTH = 19;
  const ITEM_FULL_WIDTH = THUMBNAIL_WIDTH + GAP_WIDTH;
  const THUMBNAIL_TRANSITION_DURATION = 300;

  let thumbnailsNeedCloning = false;

  let startX = 0;
  let startY = 0;
  let isDragging = false;
  const SWIPE_THRESHOLD = 70;

  const setTransition = (duration) => {
    allImages.forEach(
      (img) => (img.style.transition = `transform ${duration}ms ease-out`),
    );
  };

  const updateImageMeta = (slideIndex) => {
    if (!openImageCounter || !openImageTitle) return;

    let realIndex = slideIndex;

    if (slideIndex === 0) {
      realIndex = realSlidesCount;
    } else if (slideIndex === totalSlidesCount - 1) {
      realIndex = 1;
    }

    const currentImage = allImages[slideIndex];
    if (!currentImage) return;

    const imageAlt = currentImage.getAttribute("alt");
    const imageTitle = currentImage.getAttribute("title");

    openImageCounter.textContent = `${realIndex}/${realSlidesCount}`;
    openImageTitle.textContent = imageTitle || imageAlt || "";
  };

  const syncThumbnails = (slideIndex) => {
    if (!thumbnailsNeedCloning) {
      smallImages.forEach((img) => img.classList.remove("active"));
      let activeIndex = slideIndex;
      if (slideIndex === 0) activeIndex = realSlidesCount;
      if (slideIndex === totalSlidesCount - 1) activeIndex = 1;

      const targetThumbnailIndex = activeIndex - 1;

      if (smallImages[targetThumbnailIndex]) {
        smallImages[targetThumbnailIndex].classList.add("active");
      }
      return;
    }

    const targetThumbnailIndex = slideIndex;

    const viewport = smallWrapper.parentElement;
    if (!viewport) return;

    const viewportWidth = viewport.clientWidth;

    const currentTransformStyle =
      smallWrapper.style.transform || "translateX(0px)";
    const currentTranslateX = Math.abs(
      parseFloat(currentTransformStyle.replace(/translateX\((.*)px\)/, "$1")),
    );

    const targetLeft = targetThumbnailIndex * ITEM_FULL_WIDTH;
    const targetRight = targetLeft + THUMBNAIL_WIDTH;

    const viewportLeftBound = currentTranslateX;
    const viewportRightBound = currentTranslateX + viewportWidth - GAP_WIDTH;

    let newTranslateX = currentTranslateX;

    if (targetRight > viewportRightBound) {
      newTranslateX = targetRight - viewportWidth + GAP_WIDTH;
    } else if (targetLeft < viewportLeftBound) {
      newTranslateX = targetLeft;
    }

    const totalContentWidth = smallImages.length * ITEM_FULL_WIDTH - GAP_WIDTH;
    const maxTranslateX = Math.max(0, totalContentWidth - viewportWidth);

    newTranslateX = Math.min(newTranslateX, maxTranslateX);
    newTranslateX = Math.max(0, newTranslateX);

    smallWrapper.style.transform = `translateX(-${newTranslateX}px)`;

    smallImages.forEach((img) => img.classList.remove("active"));
    smallImages[targetThumbnailIndex].classList.add("active");
  };

  const goToSlide = (slide) => {
    setTransition(TRANSITION_DURATION);

    allImages.forEach((img, i) => {
      img.style.transform = `translateX(${100 * (i - slide)}%)`;
    });

    currSlide = slide;

    if (thumbnailsNeedCloning) {
      smallWrapper.style.transition = `transform ${THUMBNAIL_TRANSITION_DURATION}ms ease-out`;
    } else {
      smallWrapper.style.transition = `none`;
    }

    syncThumbnails(currSlide);
    updateImageMeta(currSlide);

    if (currSlide === totalSlidesCount - 1) {
      setTimeout(() => {
        setTransition(0);
        allImages.forEach((img, i) => {
          img.style.transform = `translateX(${100 * (i - 1)}%)`;
        });

        currSlide = 1;

        if (thumbnailsNeedCloning) {
          smallWrapper.style.transition = `none`;
          const S1_INDEX = 1;
          const initialTranslateX = S1_INDEX * ITEM_FULL_WIDTH;
          smallWrapper.style.transform = `translateX(-${initialTranslateX}px)`;
        }

        syncThumbnails(currSlide);
        updateImageMeta(currSlide);

        setTimeout(() => setTransition(TRANSITION_DURATION), 50);
      }, TRANSITION_DURATION);
    } else if (currSlide === 0) {
      setTimeout(() => {
        setTransition(0);
        allImages.forEach((img, i) => {
          img.style.transform = `translateX(${100 * (i - realSlidesCount)}%)`;
        });

        currSlide = realSlidesCount;

        if (thumbnailsNeedCloning) {
          smallWrapper.style.transition = `none`;

          const viewport = smallWrapper.parentElement;
          if (viewport) {
            const viewportWidth = viewport.clientWidth;
            const totalContentWidth =
              smallImages.length * ITEM_FULL_WIDTH - GAP_WIDTH;
            const maxTranslateX = Math.max(
              0,
              totalContentWidth - viewportWidth,
            );

            let newTranslateX = maxTranslateX;

            smallWrapper.style.transform = `translateX(-${newTranslateX}px)`;
          }
        }

        syncThumbnails(currSlide);
        updateImageMeta(currSlide);

        setTimeout(() => setTransition(TRANSITION_DURATION), 50);
      }, TRANSITION_DURATION);
    }
  };

  const nextImage = () => {
    goToSlide(currSlide + 1);
  };

  const prevImage = () => {
    goToSlide(currSlide - 1);
  };

  largeImageWrapper.addEventListener("mousedown", (e) => {
    if (e.button !== 0) return;
    isDragging = true;
    startX = e.clientX;
    e.preventDefault();
    setTransition(0);
  });

  largeImageWrapper.addEventListener("mousemove", (e) => {
    if (!isDragging) return;

    const currentX = e.clientX;
    const diffX = currentX - startX;

    allImages.forEach((img, i) => {
      const currentPos = 100 * (i - currSlide);
      const movePercentage = (diffX / largeImageWrapper.offsetWidth) * 100;
      img.style.transform = `translateX(${currentPos + movePercentage}%)`;
    });
  });

  largeImageWrapper.addEventListener("mouseup", (e) => {
    if (!isDragging) return;
    isDragging = false;

    const endX = e.clientX;
    const diffX = endX - startX;
    setTransition(TRANSITION_DURATION);

    if (Math.abs(diffX) >= SWIPE_THRESHOLD) {
      if (diffX > 0) {
        prevImage();
      } else {
        nextImage();
      }
    } else {
      goToSlide(currSlide);
    }
  });

  largeImageWrapper.addEventListener("mouseleave", () => {
    if (isDragging) {
      isDragging = false;
      setTransition(TRANSITION_DURATION);
      goToSlide(currSlide);
    }
  });

  largeImageWrapper.addEventListener("touchstart", (e) => {
    startX = e.touches[0].clientX;
    startY = e.touches[0].clientY;
    setTransition(0);
  });

  largeImageWrapper.addEventListener("touchmove", (e) => {
    const currentX = e.touches[0].clientX;
    const currentY = e.touches[0].clientY;

    const deltaX = currentX - startX;
    const deltaY = currentY - startY;

    if (Math.abs(deltaX) > Math.abs(deltaY)) {
      e.preventDefault();

      allImages.forEach((img, i) => {
        const currentPos = 100 * (i - currSlide);
        const movePercentage = (deltaX / largeImageWrapper.offsetWidth) * 100;
        img.style.transform = `translateX(${currentPos + movePercentage}%)`;
      });
    }
  });

  largeImageWrapper.addEventListener("touchend", (e) => {
    const endX = e.changedTouches[0].clientX;
    const diffX = endX - startX;
    setTransition(TRANSITION_DURATION);

    if (Math.abs(diffX) >= SWIPE_THRESHOLD) {
      if (diffX > 0) {
        prevImage();
      } else {
        nextImage();
      }
    } else {
      goToSlide(currSlide);
    }

    startX = 0;
    startY = 0;
  });

  if (btnNext && btnPrev) {
    btnNext.addEventListener("click", nextImage);
    btnPrev.addEventListener("click", prevImage);
  }

  document.addEventListener("keydown", (e) => {
    if (e.key === "ArrowLeft") prevImage();
    if (e.key === "ArrowRight") nextImage();
  });

  smallImages.forEach((thumbnail) => {
    thumbnail.addEventListener("click", function () {
      let targetActiveIndex = parseInt(this.getAttribute("data-active"));

      if (thumbnailsNeedCloning) {
        if (isNaN(targetActiveIndex)) return;
      } else {
        if (isNaN(targetActiveIndex)) return;
      }

      if (!isNaN(targetActiveIndex)) {
        goToSlide(targetActiveIndex);
      }
    });
  });

  const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24" fill="#fff">
<path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 5.29289C5.68342 4.90237 6.31658 4.90237 6.70711 5.29289L12 10.5858L17.2929 5.29289C17.6834 4.90237 18.3166 4.90237 18.7071 5.29289C19.0976 5.68342 19.0976 6.31658 18.7071 6.70711L13.4142 12L18.7071 17.2929C19.0976 17.6834 19.0976 18.3166 18.7071 18.7071C18.3166 19.0976 17.6834 19.0976 17.2929 18.7071L12 13.4142L6.70711 18.7071C6.31658 19.0976 5.68342 19.0976 5.29289 18.7071C4.90237 18.3166 4.90237 17.6834 5.29289 17.2929L10.5858 12L5.29289 6.70711C4.90237 6.31658 4.90237 5.68342 5.29289 5.29289Z" fill="#fff"/>
</svg>`;

  openImage.addEventListener("click", () => {
    const currentModalSlide =
      currSlide === 0
        ? realSlidesCount
        : currSlide === totalSlidesCount - 1
          ? 1
          : currSlide;

    const currentImage = allImages[currentModalSlide];
    const currentImageClone = currentImage.cloneNode(true);

    imageModal.innerHTML = "";
    sectionParent.classList.add("open-image-modal");
    body.classList.add("open-image-modal");

    imageModal.append(currentImageClone);
    imageModal.insertAdjacentHTML("beforeend", svg);
  });

  closeModal.addEventListener("click", () => {
    sectionParent.classList.remove("open-image-modal");
    body.classList.remove("open-image-modal");
    imageModal.innerHTML = "";
  });

  function init() {
    const viewport = smallWrapper.parentElement;
    if (!viewport) return;

    const viewportWidth = viewport.clientWidth;
    const initialContentWidth = realSlidesCount * ITEM_FULL_WIDTH - GAP_WIDTH;

    thumbnailsNeedCloning = initialContentWidth > viewportWidth;

    if (thumbnailsNeedCloning) {
      if (realSlidesCount > 1) {
        const firstRealThumbnail = smallImages[0];
        const lastRealThumbnail = smallImages[realSlidesCount - 1];

        const lastClone = lastRealThumbnail.cloneNode(true);
        lastClone.classList.add("cloned-thumbnail");
        lastClone.removeAttribute("data-active");

        const firstClone = firstRealThumbnail.cloneNode(true);
        firstClone.classList.add("cloned-thumbnail");
        firstClone.removeAttribute("data-active");

        smallWrapper.prepend(lastClone);
        smallWrapper.append(firstClone);

        smallImages = document.querySelectorAll(
          ".images-wrapper--container-small img",
        );
      }
    }

    setTransition(0);

    allImages.forEach((img, i) => {
      img.style.transform = `translateX(${100 * (i - currSlide)}%)`;
    });

    if (thumbnailsNeedCloning) {
      const S1_INDEX = 1;
      const initialTranslateX = S1_INDEX * ITEM_FULL_WIDTH;

      smallWrapper.style.transition = `none`;
      smallWrapper.style.transform = `translateX(-${initialTranslateX}px)`;
    } else {
      smallWrapper.style.transition = `none`;
      smallWrapper.style.transform = `translateX(0px)`;
    }

    syncThumbnails(currSlide);
    updateImageMeta(currSlide);

    setTimeout(() => setTransition(TRANSITION_DURATION), 50);
    if (thumbnailsNeedCloning) {
      setTimeout(
        () =>
          (smallWrapper.style.transition = `transform ${THUMBNAIL_TRANSITION_DURATION}ms ease-out`),
        100,
      );
    }
  }

  init();
  updateImageMeta(currSlide);
});
