import { useState } from "react";
import { Link, usePage } from "@inertiajs/react";
import { Dialog } from "@headlessui/react";
import { Bars3Icon, XMarkIcon } from "@heroicons/react/24/outline";

import Logo from '../../../public/logo1.png'

const navigation = [
    { name: "Home", href: "/" },
    { name: "Features", href: "#" },
    { name: "Dashboard", href: "/dashboard", authRequired: true },
    { name: "Contact", href: "#" },
];

export default function Navbar() {
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const { auth } = usePage().props;

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    const handleLogout = (e) => {
        e.preventDefault();

        fetch("/logout", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
        }).then((response) => {
            if (response.ok) {
                window.location.href = "/";
            }
        });
    };

    const filteredNavigation = navigation.filter(item => !item.authRequired || (item.authRequired && auth.user));

    return (
        <>
            <div className="bg-white">
                <header className="absolute inset-x-0 top-0 z-50">
                    <nav
                        className="flex items-center justify-between p-6 lg:px-8"
                        aria-label="Global"
                    >
                        <div className="flex lg:flex-1">
                            <a href="#" className="-m-1.5 p-1.5">
                                <span className="sr-only">Your Company</span>
                                {/* <img
                                    className="h-11 w-auto"
                                    src={Logo}
                                    alt=""
                                /> */}
                            </a>
                        </div>
                        <div className="flex lg:hidden">
                            <button
                                type="button"
                                className="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 "
                                onClick={() => setMobileMenuOpen(true)}
                            >
                                <span className="sr-only">Open main menu</span>
                                <Bars3Icon
                                    className="h-12 w-12"
                                    aria-hidden="true"
                                    color='#FFFFFF'
                                />
                            </button>
                        </div>
                        <div className="hidden lg:flex lg:gap-x-12">
                            {filteredNavigation.map((item) => (
                                <a
                                    key={item.name}
                                    href={item.href}
                                    className="text-md font-semibold leading-6 text-white hover:bg-white hover:text-black hover:rounded-md p-2"
                                >
                                    {item.name}
                                </a>
                            ))}
                        </div>
                        <div className="hidden lg:flex lg:flex-1 lg:justify-end">
                            {auth.user ? (
                                <a
                                    href="#"
                                    onClick={handleLogout}
                                    className="text-sm font-semibold leading-6 text-white hover:bg-white hover:text-black hover:rounded-md p-2"
                                >
                                    Sign Out{" "}
                                    <span aria-hidden="true">&rarr;</span>
                                </a>
                            ) : (
                                <>
                                    <Link
                                        href="/login"
                                        className="text-sm font-semibold leading-6 text-white hover:bg-white hover:text-black hover:rounded-md p-2"
                                    >
                                        Log in{" "}
                                        <span aria-hidden="true">&rarr;</span>
                                    </Link>
                                    <Link
                                        href="/register"
                                        className="ml-4 text-sm font-semibold leading-6 text-white hover:bg-white hover:text-black hover:rounded-md p-2"
                                    >
                                        Register{" "}
                                        <span aria-hidden="true">&rarr;</span>
                                    </Link>
                                </>
                            )}
                        </div>
                    </nav>
                    <Dialog
                        className="lg:hidden"
                        open={mobileMenuOpen}
                        onClose={setMobileMenuOpen}
                    >
                        <div className="fixed inset-0 z-50" />
                        <Dialog.Panel className="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                            <div className="flex items-center justify-between">
                                <a href="#" className="-m-1.5 p-1.5">
                                    <span className="sr-only">
                                        Your Company
                                    </span>
                                    <img
                                        className="h-8 w-auto bg-gray-900"
                                        src={Logo}
                                        alt=""
                                    />
                                </a>
                                <button
                                    type="button"
                                    className="-m-2.5 rounded-md p-2.5 text-gray-700"
                                    onClick={() => setMobileMenuOpen(false)}
                                >
                                    <span className="sr-only">Close menu</span>
                                    <XMarkIcon
                                        className="h-6 w-6"
                                        aria-hidden="true"
                                        
                                    />
                                </button>
                            </div>
                            <div className="mt-6 flow-root">
                                <div className="-my-6 divide-y divide-gray-500/10">
                                    <div className="space-y-2 py-6">
                                        {filteredNavigation.map((item) => (
                                            <a
                                                key={item.name}
                                                href={item.href}
                                                className="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50"
                                            >
                                                {item.name}
                                            </a>
                                        ))}
                                    </div>
                                    <div className="py-6">
                                        {auth.user ? (
                                            <a
                                                href="#"
                                                onClick={handleLogout}
                                                className="text-sm font-semibold leading-6 text-gray-900"
                                            >
                                                Sign Out{" "}
                                                <span aria-hidden="true">
                                                    &rarr;
                                                </span>
                                            </a>
                                        ) : (
                                            <>
                                                <Link
                                                    href="/login"
                                                    className="text-sm font-semibold leading-6 text-gray-900"
                                                >
                                                    Log in{" "}
                                                    <span aria-hidden="true">
                                                        &rarr;
                                                    </span>
                                                </Link>
                                                <Link
                                                    href="/register"
                                                    className="ml-4 text-sm font-semibold leading-6 text-gray-900"
                                                >
                                                    Register{" "}
                                                    <span aria-hidden="true">
                                                        &rarr;
                                                    </span>
                                                </Link>
                                            </>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </Dialog.Panel>
                    </Dialog>
                </header>
            </div>
        </>
    );
}
