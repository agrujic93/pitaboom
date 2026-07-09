const BIG_CARD_RADIUS = 16;
const BIG_CARD_STROKE_INSET = 0;

function buildCardBorderPaths(width, height, r = BIG_CARD_RADIUS) {
	const w = width - BIG_CARD_STROKE_INSET * 2;
	const h = height - BIG_CARD_STROKE_INSET * 2;
	const o = BIG_CARD_STROKE_INSET;
	const topCenterX = o + w / 2;
	const bottomCenterX = topCenterX;

	const rightPath = `
		M ${topCenterX},${o}
		L ${o + w - r},${o}
		A ${r},${r} 0 0 1 ${o + w},${o + r}
		L ${o + w},${o + h - r}
		A ${r},${r} 0 0 1 ${o + w - r},${o + h}
		L ${bottomCenterX},${o + h}
	`;

	const leftPath = `
		M ${topCenterX},${o}
		L ${o + r},${o}
		A ${r},${r} 0 0 0 ${o},${o + r}
		L ${o},${o + h - r}
		A ${r},${r} 0 0 0 ${o + r},${o + h}
		L ${bottomCenterX},${o + h}
	`;

	return { rightPath, leftPath };
}

function initCardBorderDraw(card, index) {
	const svg = card.querySelector('.big-card-border-draw');
	const rightEl = card.querySelector('.big-card-border-path--right');
	const leftEl = card.querySelector('.big-card-border-path--left');

	if (!svg || !rightEl || !leftEl) return;

	// unique gradient ID per card
	const gradientId = `big-cardBorderGradient-${index}`;
	const gradientEl = svg.querySelector('linearGradient');
	if (gradientEl) gradientEl.setAttribute('id', gradientId);
	rightEl.setAttribute('stroke', `url(#${gradientId})`);
	leftEl.setAttribute('stroke', `url(#${gradientId})`);

	let tl;

	function setup() {
		if (tl) tl.kill();

		const rect = card.getBoundingClientRect();
		const width = Math.round(rect.width);
		const height = Math.round(rect.height);

		svg.setAttribute('viewBox', `0 0 ${width} ${height}`);

		const { rightPath, leftPath } = buildCardBorderPaths(width, height);
		rightEl.setAttribute('d', rightPath.trim());
		leftEl.setAttribute('d', leftPath.trim());

		const originalDisplay = svg.style.display;
		svg.style.display = 'block';
		const rightLen = rightEl.getTotalLength() || 0;
		const leftLen = leftEl.getTotalLength() || 0;
		svg.style.display = originalDisplay;

		gsap.set([rightEl, leftEl], { clearProps: 'strokeDasharray,strokeDashoffset' });
		gsap.set(rightEl, { strokeDasharray: rightLen, strokeDashoffset: rightLen });
		gsap.set(leftEl, { strokeDasharray: leftLen, strokeDashoffset: leftLen });

		tl = gsap.timeline({
			paused: true,
			onStart: () => {
				svg.style.display = 'block';
			},
			onReverseComplete: () => {
				svg.style.display = 'none';
			},
		});

		tl.to(rightEl, { strokeDashoffset: 0, duration: 0.8, ease: 'power2.inOut' }, 0);
		tl.to(leftEl, { strokeDashoffset: 0, duration: 0.8, ease: 'power2.inOut' }, 0);
	}

	const observer = new ResizeObserver(() => {
		const wasActive = card.matches(':hover');
		setup();
		if (wasActive) tl.progress(1);
	});

	observer.observe(card);

	card.addEventListener('mouseenter', () => tl.play());
	card.addEventListener('mouseleave', () => tl.reverse());
}

document.querySelectorAll('.custom-big-card-component').forEach((card, index) => {
	initCardBorderDraw(card, index);
});