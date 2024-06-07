import React, { useState } from "react";
import {
    ChevronLeftIcon,
    BanknotesIcon,
    HomeIcon,
    InboxIcon,
    AdjustmentsHorizontalIcon,
    CreditCardIcon,
    ChartPieIcon,
    UserGroupIcon,
    CurrencyDollarIcon,
    ShareIcon,
    CogIcon,
    ArrowRightEndOnRectangleIcon,
} from "@heroicons/react/24/outline";

import { Link } from "@inertiajs/react";

export default function Sidebar() {
    const [open, setOpen] = useState(true);
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

    const Menus = [
        { title: "Home", icon: <HomeIcon className="h-6 w-6" />, href: "/" },
        {
            title: "Inbox",
            icon: <InboxIcon className="h-6 w-6" />,
            href: route("inbox"),
        },
        {
            title: "Dashboard",
            icon: <AdjustmentsHorizontalIcon className="h-6 w-6" />,
            gap: true,
            href: "/dashboard",
        },
        {
            title: "Card",
            icon: <CreditCardIcon className="h-6 w-6" />,
            href: route("card"),
        },
        {
            title: "Statistics",
            icon: <ChartPieIcon className="h-6 w-6" />,
            href: route("statistics"),
        },
        {
            title: "Recipients",
            icon: <UserGroupIcon className="h-6 w-6" />,
            href: route("recipients"),
        },
        {
            title: "Transactions",
            icon: <CurrencyDollarIcon className="h-6 w-6" />,
            href: route("transactions"),
        },
        {
            title: "Referral",
            icon: <ShareIcon className="h-6 w-6" />,
            gap: true,
            href: route("referral"),
        },
        {
            title: "Sign Out",
            icon: <ArrowRightEndOnRectangleIcon className="h-6 w-6" />,
            gap: true,
            logout: true,
        },
    ];

    return (
        <div>
            <div
                className={`${
                    open ? "w-72" : "w-20"
                }  h-screen p-5 pt-8 bg-white relative duration-300`}
            >
                <ChevronLeftIcon
                    className={`size-9 absolute cursor-pointer -right-4 top-9 border-2 border-white bg-white rounded-full z-50 text-sky-600 ${
                        !open && "rotate-180"
                    }`}
                    onClick={() => setOpen(!open)}
                />
                <div className="flex gap-x-4 items-center">
                    <BanknotesIcon
                        className={`size-9 cursor-pointer duration-600 text-cyan-600 ${
                            open && "rotate-[360deg]"
                        }`}
                    />
                    <h1
                        className={`text-cyan-600 origin-left font-medium text-xl duraion-500  ${
                            !open && "hidden"
                        }`}
                    >
                        Global Transfer
                    </h1>
                </div>
                <ul className="pt-6">
                    {Menus.map((menu, index) => (
                        <li
                            className={`text-black text-sm flex items-center gap-x-4 cursor-pointer p-2 hover:bg-cyan-100 hover:text-black rounded-xl ${
                                menu.gap ? "mt-9" : "mt-2"
                            }`}
                            key={index}
                            onClick={menu.logout ? handleLogout : null}
                        >
                            <Link href={menu.href}>{menu.icon}</Link>
                            <div
                                className={`${
                                    !open && "hidden"
                                } origin-left duration-200`}
                            >
                                <Link href={menu.href}>{menu.title}</Link>
                            </div>
                        </li>
                    ))}
                </ul>
            </div>
        </div>
    );
}
