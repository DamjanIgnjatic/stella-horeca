document.addEventListener("DOMContentLoaded", function () {
  const btnNext = document.querySelector(".arrow-next");
  const btnPrev = document.querySelector(".arrow-prev");
  const allImages = document.querySelectorAll(".images-wrapper--large img");
  const html = document.documentElement;

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

  const MOBILE_BREAKPOINT = 991;
  const PEEK_RATIO = 0.72;
  const MOBILE_GAP = 6;

  let isMobile = window.innerWidth <= MOBILE_BREAKPOINT;

  const getMobileSlideWidth = () =>
    largeImageWrapper.offsetWidth * PEEK_RATIO + MOBILE_GAP;

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

  const setMobilePositions = (slide, duration = TRANSITION_DURATION) => {
    const slideWidth = getMobileSlideWidth();
    allImages.forEach((img, i) => {
      img.style.transition = `transform ${duration}ms ease-out`;
      img.style.transform = `translateX(${(i - slide) * slideWidth}px)`;
    });
  };

  const goToSlideMobile = (slide) => {
    setMobilePositions(slide);
    currSlide = slide;
    updateImageMeta(currSlide);
    syncThumbnails(currSlide);

    if (currSlide === totalSlidesCount - 1) {
      setTimeout(() => {
        setMobilePositions(1, 0);
        currSlide = 1;
        updateImageMeta(currSlide);
        syncThumbnails(currSlide);
        setTimeout(() => {
          allImages.forEach(
            (img) =>
              (img.style.transition = `transform ${TRANSITION_DURATION}ms ease-out`),
          );
        }, 50);
      }, TRANSITION_DURATION);
    }

    if (currSlide === 0) {
      setTimeout(() => {
        setMobilePositions(realSlidesCount, 0);
        currSlide = realSlidesCount;
        updateImageMeta(currSlide);
        syncThumbnails(currSlide);
        setTimeout(() => {
          allImages.forEach(
            (img) =>
              (img.style.transition = `transform ${TRANSITION_DURATION}ms ease-out`),
          );
        }, 50);
      }, TRANSITION_DURATION);
    }
  };

  const goToSlide = (slide) => {
    isMobile = window.innerWidth <= MOBILE_BREAKPOINT;

    if (isMobile) {
      goToSlideMobile(slide);
      return;
    }

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

    isMobile = window.innerWidth <= MOBILE_BREAKPOINT;
    if (!isMobile) setTransition(0);
  });

  largeImageWrapper.addEventListener("mousemove", (e) => {
    if (!isDragging) return;

    const currentX = e.clientX;
    const diffX = currentX - startX;

    isMobile = window.innerWidth <= MOBILE_BREAKPOINT;

    if (isMobile) {
      const slideWidth = getMobileSlideWidth();
      allImages.forEach((img, i) => {
        img.style.transition = "none";
        const basePos = (i - currSlide) * slideWidth;
        img.style.transform = `translateX(${basePos + diffX}px)`;
      });
    } else {
      allImages.forEach((img, i) => {
        const currentPos = 100 * (i - currSlide);
        const movePercentage = (diffX / largeImageWrapper.offsetWidth) * 100;
        img.style.transform = `translateX(${currentPos + movePercentage}%)`;
      });
    }
  });

  largeImageWrapper.addEventListener("mouseup", (e) => {
    if (!isDragging) return;
    isDragging = false;

    const endX = e.clientX;
    const diffX = endX - startX;

    isMobile = window.innerWidth <= MOBILE_BREAKPOINT;
    if (!isMobile) setTransition(TRANSITION_DURATION);

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
      isMobile = window.innerWidth <= MOBILE_BREAKPOINT;
      if (!isMobile) setTransition(TRANSITION_DURATION);
      goToSlide(currSlide);
    }
  });

  largeImageWrapper.addEventListener("touchstart", (e) => {
    startX = e.touches[0].clientX;
    startY = e.touches[0].clientY;

    isMobile = window.innerWidth <= MOBILE_BREAKPOINT;
    if (!isMobile) setTransition(0);
  });

  largeImageWrapper.addEventListener(
    "touchmove",
    (e) => {
      const currentX = e.touches[0].clientX;
      const currentY = e.touches[0].clientY;

      const deltaX = currentX - startX;
      const deltaY = currentY - startY;

      if (Math.abs(deltaX) > Math.abs(deltaY)) {
        e.preventDefault();

        isMobile = window.innerWidth <= MOBILE_BREAKPOINT;

        if (isMobile) {
          const slideWidth = getMobileSlideWidth();
          allImages.forEach((img, i) => {
            img.style.transition = "none";
            const basePos = (i - currSlide) * slideWidth;
            img.style.transform = `translateX(${basePos + deltaX}px)`;
          });
        } else {
          allImages.forEach((img, i) => {
            const currentPos = 100 * (i - currSlide);
            const movePercentage =
              (deltaX / largeImageWrapper.offsetWidth) * 100;
            img.style.transform = `translateX(${currentPos + movePercentage}%)`;
          });
        }
      }
    },
    { passive: false },
  );

  largeImageWrapper.addEventListener("touchend", (e) => {
    const endX = e.changedTouches[0].clientX;
    const diffX = endX - startX;

    isMobile = window.innerWidth <= MOBILE_BREAKPOINT;
    if (!isMobile) setTransition(TRANSITION_DURATION);

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

      if (isNaN(targetActiveIndex)) return;

      goToSlide(targetActiveIndex);
    });
  });

  const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24" fill="#000">
<path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 5.29289C5.68342 4.90237 6.31658 4.90237 6.70711 5.29289L12 10.5858L17.2929 5.29289C17.6834 4.90237 18.3166 4.90237 18.7071 5.29289C19.0976 5.68342 19.0976 6.31658 18.7071 6.70711L13.4142 12L18.7071 17.2929C19.0976 17.6834 19.0976 18.3166 18.7071 18.7071C18.3166 19.0976 17.6834 19.0976 17.2929 18.7071L12 13.4142L6.70711 18.7071C6.31658 19.0976 5.68342 19.0976 5.29289 18.7071C4.90237 18.3166 4.90237 17.6834 5.29289 17.2929L10.5858 12L5.29289 6.70711C4.90237 6.31658 4.90237 5.68342 5.29289 5.29289Z" fill="#000"/>
</svg>`;

  const openModalForCurrentSlide = () => {
    let currentImage;

    if (allImages.length === 1) {
      currentImage = allImages[0];
    } else {
      const currentModalSlide =
        currSlide === 0
          ? realSlidesCount
          : currSlide === totalSlidesCount - 1
            ? 1
            : currSlide;

      currentImage = allImages[currentModalSlide];
    }

    if (!currentImage) return;

    const currentImageClone = currentImage.cloneNode(true);

    imageModal.innerHTML = "";
    sectionParent.classList.add("open-image-modal");
    html.classList.add("open-image-modal");

    imageModal.append(currentImageClone);
    imageModal.insertAdjacentHTML("beforeend", svg);
  };

  openImage.addEventListener("click", () => {
    if (window.innerWidth <= MOBILE_BREAKPOINT) return;
    openModalForCurrentSlide();
  });

  let pointerMoved = false;

  largeImageWrapper.addEventListener("pointerdown", () => {
    pointerMoved = false;
  });

  largeImageWrapper.addEventListener("pointermove", () => {
    pointerMoved = true;
  });

  largeImageWrapper.addEventListener("pointerup", () => {
    if (window.innerWidth > MOBILE_BREAKPOINT) return;
    if (pointerMoved) return; // bio je swipe, ne tap
    openModalForCurrentSlide();
  });

  closeModal.addEventListener("click", () => {
    sectionParent.classList.remove("open-image-modal");
    html.classList.remove("open-image-modal");
    imageModal.innerHTML = "";
  });

  let resizeTimer;
  window.addEventListener("resize", () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      const wasMobile = isMobile;
      isMobile = window.innerWidth <= MOBILE_BREAKPOINT;

      if (isMobile) {
        setMobilePositions(currSlide, 0);
      } else if (wasMobile && !isMobile) {
        setTransition(0);
        allImages.forEach((img, i) => {
          img.style.transform = `translateX(${100 * (i - currSlide)}%)`;
        });
        setTimeout(() => setTransition(TRANSITION_DURATION), 50);
      }
    }, 100);
  });

  function init() {
    if (allImages.length === 1) {
      currSlide = 0;
      updateImageMeta(0);

      if (openImageCounter) openImageCounter.style.display = "none";
      if (btnNext) btnNext.style.display = "none";
      if (btnPrev) btnPrev.style.display = "none";

      return;
    }

    isMobile = window.innerWidth <= MOBILE_BREAKPOINT;

    const viewport = smallWrapper ? smallWrapper.parentElement : null;

    if (!isMobile && viewport) {
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
    }

    if (isMobile) {
      setMobilePositions(currSlide, 0);
    } else {
      setTransition(0);
      allImages.forEach((img, i) => {
        img.style.transform = `translateX(${100 * (i - currSlide)}%)`;
      });

      if (smallWrapper) {
        if (thumbnailsNeedCloning) {
          const S1_INDEX = 1;
          const initialTranslateX = S1_INDEX * ITEM_FULL_WIDTH;
          smallWrapper.style.transition = `none`;
          smallWrapper.style.transform = `translateX(-${initialTranslateX}px)`;
        } else {
          smallWrapper.style.transition = `none`;
          smallWrapper.style.transform = `translateX(0px)`;
        }
      }

      setTimeout(() => setTransition(TRANSITION_DURATION), 50);
      if (thumbnailsNeedCloning && smallWrapper) {
        setTimeout(
          () =>
            (smallWrapper.style.transition = `transform ${THUMBNAIL_TRANSITION_DURATION}ms ease-out`),
          100,
        );
      }
    }

    syncThumbnails(currSlide);
    updateImageMeta(currSlide);
  }

  init();
  updateImageMeta(currSlide);
});
