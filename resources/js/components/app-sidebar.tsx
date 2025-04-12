import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { useTranslation } from '@/hooks/use-translation';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { BookOpen, Folder, LayoutGrid, SettingsIcon, ShieldIcon, UsersIcon } from 'lucide-react';
import { useMemo } from 'react';
import AppLogo from './app-logo';

export function AppSidebar() {
    const { __ } = useTranslation();

    const mainNavItems: NavItem[] = useMemo(
        () => [
            {
                title: __('Dashboard'),
                href: '/dashboard',
                icon: LayoutGrid,
            },
            {
                title: __('Users'),
                href: '/users',
                icon: UsersIcon,
            },
            {
                title: __('Roles'),
                href: '/roles',
                icon: ShieldIcon,
            },
            {
                title: __('Permissions'),
                href: '/permissions',
                icon: ShieldIcon,
            },
            {
                title: __('Settings'),
                href: '/settings',
                icon: SettingsIcon,
            },
        ],
        [__],
    );

    const footerNavItems: NavItem[] = useMemo(
        () => [
            {
                title: __('Repository'),
                href: 'https://github.com/laravel/react-starter-kit',
                icon: Folder,
            },
            {
                title: __('Documentation'),
                href: 'https://laravel.com/docs/starter-kits',
                icon: BookOpen,
            },
        ],
        [__],
    );

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/dashboard" prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
