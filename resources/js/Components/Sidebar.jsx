import React, { useState } from "react";
import {
    ChevronLeftIcon,
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
    ArrowsRightLeftIcon,
} from "@heroicons/react/24/outline";

import { Link } from "@inertiajs/react";
import DashboardNavbar from "./DashboardNavbar";

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
            title: "Dashboard",
            icon: <AdjustmentsHorizontalIcon className="h-6 w-6" />,
            // gap: true,
            href: "/dashboard",
        },
        {
            title: "Transactions",
            icon: <CurrencyDollarIcon className="h-6 w-6" />,
            href: route("transactions"),
        },
        {
            title: "Accounts",
            icon: <UserGroupIcon className="h-6 w-6" />,
            href: route("accounts"),
        },
        {
            title: "Converter",
            icon: <ArrowsRightLeftIcon className="h-6 w-6" />,
            href: route("converter"),
        },
        {
            title: "Cards",
            icon: <CreditCardIcon className="h-6 w-6" />,
            href: route("card"),
        },
        {
            title: "Recipients",
            icon: <UserGroupIcon className="h-6 w-6" />,
            href: route("recipients"),
        },
        {
            title: "Referral",
            icon: <ShareIcon className="h-6 w-6" />,
            // gap: true,
            href: route("referral"),
        },
        {
            title: "Settings",
            icon: <CogIcon className="h-6 w-6" />,
            gap: true,
            href: route("settings"),
        },
        {
            title: "Sign Out",
            icon: <ArrowRightEndOnRectangleIcon className="h-6 w-6" />,
            // gap: true,
            logout: true,
        },
    ];

    return (
        <div>
            <div
                className={`${
                    open ? "w-72" : "w-20"
                }  h-screen p-5 pt-1 bg-white relative duration-300`}
            >
                <ChevronLeftIcon
                    className={`size-9 absolute cursor-pointer -right-4 top-9 border-2 border-white bg-white rounded-full z-50 text-gray-600 ${
                        !open && "rotate-180"
                    }`}
                    onClick={() => setOpen(!open)}
                />

                <ul className="pt-6">
                    {Menus.map((menu, index) => (
                        <li
                            className={`text-gray-600 text-sm flex items-center gap-x-4 cursor-pointer p-2 hover:border-l-4 hover:border-blue-700 hover:text-blue-700 rounded-xl ${
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
