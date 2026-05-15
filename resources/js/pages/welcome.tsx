import { Head } from '@inertiajs/react';

export default function Welcome() {
    return (
        <>
            <Head title="Top Robbers - Coming Soon" />

            <main className="min-h-screen overflow-hidden bg-[#17151f] text-[#f7f4ea]">
                <div className="relative flex min-h-screen items-center justify-center px-6">
                    <div className="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(144,131,210,0.18),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(240,180,41,0.12),transparent_30%)]" />

                    <div className="absolute left-8 top-8 text-sm font-semibold uppercase tracking-[0.35em] text-[#9083d2]">
                        Top Robbers
                    </div>

                    <section className="relative z-10 mx-auto max-w-4xl text-center">
                        <div className="mb-8 inline-flex items-center gap-3 rounded-full border border-[#9083d2]/30 bg-white/5 px-5 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-[#9083d2] backdrop-blur">
                            <span className="relative flex h-2.5 w-2.5">
                                <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#f0b429] opacity-75" />
                                <span className="relative inline-flex h-2.5 w-2.5 rounded-full bg-[#f0b429]" />
                            </span>

                            The heist is loading ...
                        </div>

                        <div className="mx-auto mb-8 flex h-24 w-24 items-center justify-center rounded-3xl border border-[#9083d2]/30 bg-[#211f2b] shadow-2xl shadow-[#9083d2]/10">
                            <CrownIcon />
                        </div>

                        <h1 className="text-5xl font-black uppercase tracking-tight text-[#f7f4ea] sm:text-7xl lg:text-8xl">
                            Top
                            <span className="block text-[#f0b429]">Robbers</span>
                        </h1>

                        <p className="mx-auto mt-8 max-w-2xl text-base leading-8 text-[#d8d2f0]/80 sm:text-lg">
                            <strong>Top Robbers</strong> is preparing its comeback!<br /><span className='text-sm'>A new platform is coming ...</span>
                        </p>

                        <div className="mt-10 flex flex-col items-center justify-center gap-5">
                            <a
                                href="https://discord.gg/"
                                className="rounded-xl bg-[#9083d2] px-7 py-3 text-sm font-bold uppercase tracking-[0.18em] text-white transition hover:-translate-y-0.5 hover:bg-[#7d70c5]"
                            >
                                Join Discord
                            </a>

                            <span className="text-xs font-semibold uppercase tracking-[0.25em] text-[#f7f4ea]/40">
                               Platform in development
                            </span>
                        </div>
                    </section>

                    <div className="absolute bottom-8 text-center text-xs font-medium uppercase tracking-[0.25em] text-[#f7f4ea]/35">
                        © 2026 - Powered by <a href="https://github.com/aurora-mp" target="_blank">Aurora Multiplayer</a>
                    </div>
                </div>
            </main>
        </>
    );
}

function CrownIcon() {
    return (
        <svg
            viewBox="0 0 96 72"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            className="h-14 w-14"
            aria-hidden="true"
        >
            <path
                d="M10 24L27 42L48 12L69 42L86 24L78 60H18L10 24Z"
                fill="#9083d2"
            />
            <path
                d="M18 64H78"
                stroke="#f0b429"
                strokeWidth="8"
                strokeLinecap="round"
            />
            <circle cx="10" cy="24" r="6" fill="#f0b429" />
            <circle cx="48" cy="12" r="6" fill="#f0b429" />
            <circle cx="86" cy="24" r="6" fill="#f0b429" />
        </svg>
    );
}