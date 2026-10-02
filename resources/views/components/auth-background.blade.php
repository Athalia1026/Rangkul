{{-- Fade the decoration at the panel edges so long forms never reveal a hard cutoff. --}}
<div
    aria-hidden="true"
    style="position: absolute; inset: 0; overflow: hidden; pointer-events: none; z-index: 0;
        -webkit-mask-image: linear-gradient(to bottom, transparent, #000 12%, #000 72%, transparent);
        mask-image: linear-gradient(to bottom, transparent, #000 12%, #000 72%, transparent);"
>
    <div style="position: absolute; inset: 0; filter: blur(28px);
        background:
            radial-gradient(ellipse 260px 230px at 92% 15%, rgba(72, 202, 154, .28), rgba(72, 202, 154, .09) 45%, transparent 75%),
            radial-gradient(ellipse 230px 260px at 5% 64%, rgba(58, 191, 141, .22), rgba(58, 191, 141, .07) 45%, transparent 75%),
            radial-gradient(ellipse 190px 190px at 88% 82%, rgba(86, 212, 166, .18), transparent 75%);"
    ></div>
</div>
