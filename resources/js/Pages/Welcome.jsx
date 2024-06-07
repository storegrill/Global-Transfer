import { Head } from "@inertiajs/react";
import Navbar from "../Components/Navbar";

export default function Welcome({ auth }) {
    return (
        <>
            <Head title="Welcome" />
            <div className="min-h-screen bg-gradient-to-b from-cyan-600 to-cyan-100 flex flex-col">
                <Navbar auth={auth} />
                <div className="relative isolate px-6 pt-14 lg:px-8">
                    <div className="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                        <div className="hidden sm:mb-8 sm:flex sm:justify-center">
                            <div className="relative rounded-full px-3 py-1 text-sm leading-6 text-white ring-1 ring-white hover:ring-gray-900/20">
                                Announcing our next round of funding.{" "}
                                <a
                                    href="#"
                                    className="font-semibold text-black"
                                >
                                    <span
                                        className="absolute inset-0"
                                        aria-hidden="true"
                                    />
                                    Read more{" "}
                                    <span aria-hidden="true">&rarr;</span>
                                </a>
                            </div>
                        </div>
                        <div className="text-center">
                            <h1 className="text-3xl font-black tracking-tight text-white sm:text-7xl">
                                Revise your approach to managing finances.
                            </h1>
                            <p className="mt-6 text-lg leading-8 text-gray-400">
                                Lorem ipsum dolor sit amet consectetur
                                adipisicing elit. Numquam natus fuga cum ipsam
                                tempore eos dolorum blanditiis aperiam quae
                                accusantium!
                            </p>
                            <div className="mt-10 flex items-center justify-center gap-x-6">
                                <a
                                    href="#"
                                    className="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                >
                                    Get started
                                </a>
                                <a
                                    href="#"
                                    className="text-sm font-semibold leading-6 text-white"
                                >
                                    Learn more <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
