/**
 * GSAP Animations - Reveal Text Effects
 *
 * Uses GSAP with ScrollTrigger for text reveal animations.
 * Uses SplitText (local vendor file) for per-word/per-line splitting.
 * Classes: .reveal-text (word reveal), .reveal-lines (line reveal)
 */

// Only initialize if we're in a browser environment
if (typeof window !== "undefined") {
  let isInitialized = false;

  const revealFallback = () => {
    document
      .querySelectorAll(".reveal-text, .reveal-lines")
      .forEach((element) => {
        element.style.visibility = "visible";
        element.style.opacity = "1";
        element.querySelectorAll("p").forEach((paragraph) => {
          paragraph.style.visibility = "visible";
          paragraph.style.opacity = "1";
        });
      });
  };

  // Check if GSAP is loaded from theme-enqueued assets
  const initializeAnimations = () => {
    if (isInitialized) return;

    if (typeof window.gsap === "undefined") {
      console.warn("GSAP not loaded. Animations will not run.");
      revealFallback();
      return;
    }

    const { gsap: gsapLib, ScrollTrigger, SplitText, SplitType } = window;

    if (!ScrollTrigger) {
      console.warn("ScrollTrigger not loaded. Animations will not run.");
      revealFallback();
      return;
    }

    if (SplitText) {
      gsapLib.registerPlugin(ScrollTrigger, SplitText);
    } else {
      gsapLib.registerPlugin(ScrollTrigger);
    }

    const hasSplitText = typeof SplitText !== "undefined";
    const hasSplitType = typeof SplitType !== "undefined";
    const hasSplitEngine = hasSplitText || hasSplitType;
    const revealTriggerStart = "top 95%";

    const createSplit = (target, type) => {
      if (hasSplitText) {
        return SplitText.create(target, { type });
      }

      if (hasSplitType) {
        return new SplitType(target, { types: type });
      }

      return null;
    };

    const wrapSplitTargets = (targets, { outerClass, innerClass }) => {
      const innerElements = [];

      targets.forEach((target) => {
        target.classList.add(outerClass);
        const content = target.textContent;
        target.textContent = "";
        const inner = document.createElement("span");
        inner.className = innerClass;
        inner.textContent = content;
        target.appendChild(inner);
        innerElements.push(inner);
      });

      return innerElements;
    };

    /**
     * Wrap SplitText line nodes for overflow masking without flattening
     * nested HTML (e.g. .area-thin / .area-strong spans).
     */
    const wrapLineTargets = (lines) => {
      const innerElements = [];

      lines.forEach((line) => {
        if (line.parentElement?.classList.contains("fdc-line-outer")) {
          line.classList.add("fdc-line-inner");
          innerElements.push(line);
          return;
        }

        const outer = document.createElement("div");
        outer.className = "fdc-line-outer";
        line.classList.add("fdc-line-inner");
        line.parentNode.insertBefore(outer, line);
        outer.appendChild(line);
        innerElements.push(line);
      });

      return innerElements;
    };

    const getRevealLineTargets = (element) => {
      const textBlocks = element.querySelectorAll("h1, h2, h3, h4, h5, h6, p");
      if (textBlocks.length) return textBlocks;

      return [element];
    };

    isInitialized = true;

    const revealTextElement = (element, { immediate = false } = {}) => {
      if (element.dataset.animated) return;
      element.dataset.animated = true;

      try {
        if (!hasSplitEngine) {
          gsapLib.fromTo(
            element,
            {
              y: 32,
              autoAlpha: 0,
            },
            {
              duration: 1.0,
              y: 0,
              autoAlpha: 1,
              ease: "power3.out",
              ...(immediate
                ? {}
                : {
                    scrollTrigger: {
                      trigger: element,
                      start: revealTriggerStart,
                      toggleActions: "play none none none",
                      once: true,
                    },
                  }),
            },
          );
          return;
        }

        const split = createSplit(element, "words");
        const innerWords = wrapSplitTargets((split && split.words) || [], {
          outerClass: "fdc-word-outer",
          innerClass: "fdc-word-inner",
        });

        gsapLib.set(innerWords, { y: "100%", autoAlpha: 0 });
        gsapLib.set(element, { autoAlpha: 1 });

        gsapLib.to(innerWords, {
          duration: 1.2,
          y: "0%",
          autoAlpha: 1,
          stagger: 0.05,
          ease: "power4.out",
          ...(immediate
            ? {}
            : {
                scrollTrigger: {
                  trigger: element,
                  start:
                    window.innerWidth <= 768 ? "top 100%" : revealTriggerStart,
                  toggleActions: "play none none none",
                  once: true,
                  markers: false,
                },
              }),
        });
      } catch (e) {
        console.error("Error initializing reveal-text animation:", e);
        element.style.visibility = "visible";
        element.style.opacity = "1";
      }
    };

    const replayManualRevealText = (element) => {
      if (!element.dataset.animated) {
        revealTextElement(element, { immediate: true });
        return;
      }

      gsapLib.set(element, { autoAlpha: 1 });

      const innerWords = element.querySelectorAll(".fdc-word-inner");
      if (innerWords.length > 0) {
        gsapLib.killTweensOf(innerWords);
        gsapLib.set(innerWords, { y: "100%", opacity: 0 });
        gsapLib.to(innerWords, {
          duration: 1.2,
          y: "0%",
          opacity: 1,
          stagger: 0.05,
          ease: "power4.out",
        });
        return;
      }

      gsapLib.killTweensOf(element);
      gsapLib.set(element, { autoAlpha: 1 });
      gsapLib.fromTo(
        element,
        { y: 32, opacity: 0 },
        {
          duration: 1.0,
          y: 0,
          opacity: 1,
          ease: "power3.out",
        },
      );
    };

    /**
     * Word-by-word reveal animation
     */
    const initRevealText = () => {
      const elements = document.querySelectorAll(".reveal-text");

      if (elements.length === 0) return;

      const isSmallViewport = window.innerWidth < 480;

      elements.forEach((element) => {
        if (element.dataset.animated) return;
        if (element.dataset.manualReveal === "true") return;
        if (element.closest('[data-manual-reveal="true"]')) return;

        const isInHeroAside = !!element.closest(".hero-aside");
        const useImmediate = isSmallViewport && isInHeroAside;

        revealTextElement(element, { immediate: useImmediate });
      });
    };

    /**
     * Line-by-line reveal animation
     */
    const initRevealLines = () => {
      const elements = document.querySelectorAll(".reveal-lines");

      if (elements.length === 0) return;

      elements.forEach((element) => {
        if (element.dataset.animated) return;
        element.dataset.animated = true;

        try {
          const targets = getRevealLineTargets(element);
          const isDelayed = element.classList.contains("reveal-delay");
          const heroParent = isDelayed ? element.closest(".ci-hero-block") : null;
          // For reveal-delay inside a hero, fire after just 8px of page scroll
          // using document.documentElement as trigger so it's immune to header offsets.
          const scrollTriggerEl = heroParent ? document.documentElement : element;
          const triggerStart    = heroParent ? 1 : (element.dataset.revealStart || revealTriggerStart);
          const revealDelay = isDelayed
            ? parseFloat(element.dataset.revealDelay) || 0.5
            : 0;

          if (!hasSplitEngine) {
            gsapLib.set(element, { autoAlpha: 1 });
            gsapLib.fromTo(
              targets,
              {
                y: 30,
                autoAlpha: 0,
              },
              {
                delay: revealDelay,
                duration: 1.0,
                y: 0,
                autoAlpha: 1,
                stagger: 0.1,
                ease: "power3.out",
                scrollTrigger: {
                  trigger: scrollTriggerEl,
                  start: triggerStart,
                  toggleActions: "play none none none",
                  once: true,
                },
              },
            );
            return;
          }

          const allLines = [];
          targets.forEach((target) => {
            const split = createSplit(target, "lines");
            const innerLines = wrapLineTargets((split && split.lines) || []);
            allLines.push(...innerLines);
          });

          gsapLib.set(allLines, { y: "100%", autoAlpha: 0 });
          gsapLib.set(element, { autoAlpha: 1 });
          gsapLib.to(allLines, {
            delay: revealDelay,
            duration: 1.2,
            y: "0%",
            autoAlpha: 1,
            stagger: 0.1,
            ease: "power3.out",
            scrollTrigger: {
              trigger: scrollTriggerEl,
              start: triggerStart,
              toggleActions: "play none none none",
              once: true,
            },
          });
        } catch (e) {
          console.error("Error initializing reveal-lines animation:", e);
          element.style.visibility = "visible";
          element.style.opacity = "1";
          element.querySelectorAll("h1, h2, h3, h4, h5, h6, p").forEach((textNode) => {
            textNode.style.visibility = "visible";
            textNode.style.opacity = "1";
          });
        }
      });
    };

    /**
     * Animate statistics big numbers with GSAP counter.
     */
    const initStatisticsCounters = () => {
      const counters = document.querySelectorAll(
        ".ci-statistics-block .stat-big-number",
      );
      if (counters.length === 0) return;

      const prefersReducedMotion = window.matchMedia(
        "(prefers-reduced-motion: reduce)",
      ).matches;

      const parseCounterValue = (rawValue) => {
        const value = String(rawValue || "").trim();
        const match = value.match(/-?\d[\d,.]*/);
        if (!match) return null;

        const numericToken = match[0];
        const prefix = value.slice(0, match.index);
        const suffix = value.slice((match.index || 0) + numericToken.length);
        const normalized = numericToken.replace(/,/g, "");
        const target = Number.parseFloat(normalized);
        if (!Number.isFinite(target)) return null;

        const decimalPart = normalized.split(".")[1] || "";
        const decimals = decimalPart.length;

        return {
          prefix,
          suffix,
          target,
          decimals,
        };
      };

      counters.forEach((counter) => {
        if (counter.dataset.counterAnimated) return;
        counter.dataset.counterAnimated = "true";

        const parsed = parseCounterValue(counter.textContent);
        if (!parsed) return;

        const { prefix, suffix, target, decimals } = parsed;

        const renderValue = (nextValue) => {
          const formatted = Number(nextValue).toLocaleString(undefined, {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals,
          });
          counter.textContent = `${prefix}${formatted}${suffix}`;
        };

        if (prefersReducedMotion) {
          renderValue(target);
          return;
        }

        ScrollTrigger.create({
          trigger: counter,
          start: window.innerWidth <= 768 ? "top 105%" : revealTriggerStart,
          once: true,
          markers: false,
          onEnter: () => {
            const state = { value: 0 };
            gsapLib.to(state, {
              value: target,
              duration: 2.4,
              ease: "sine.inOut",
              onUpdate: () => {
                renderValue(state.value);
              },
              onComplete: () => {
                renderValue(target);
              },
            });
          },
        });
      });
    };

    /**
     * Initialize all animations
     */
    const init = () => {
      //initScrollSmoother(); // Must wrap DOM and initialize ScrollSmoother first
      initRevealText();
      initRevealLines();
      initStatisticsCounters();
      // Refresh ScrollTrigger after a delay to ensure positions are correct
      setTimeout(() => {
        if (window.ScrollTrigger) {
          ScrollTrigger.refresh();
        }
      }, 200);

      console.log("GSAP Animations Initialized");
    };

    // Run on DOM ready
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", init);
    } else {
      init();
    }

    // Also run on window load to catch dynamically added content
    window.addEventListener("load", () => {
      if (window.ScrollTrigger) {
        ScrollTrigger.refresh();
      }
    });
  };

  // Wait for GSAP to be available globally
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initializeAnimations);
  } else {
    initializeAnimations();
  }

  // Also check after a short delay in case scripts load asynchronously
  setTimeout(initializeAnimations, 500);
}
