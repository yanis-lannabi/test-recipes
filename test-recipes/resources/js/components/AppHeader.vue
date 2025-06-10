<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { NavigationMenu, NavigationMenuItem, NavigationMenuList, navigationMenuTriggerStyle } from '@/components/ui/navigation-menu';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { getInitials } from '@/composables/useInitials';
import type { BreadcrumbItem, NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, Menu, Search } from 'lucide-vue-next';
import { computed } from 'vue';

interface Props {
    breadcrumbs?: BreadcrumbItem[];
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();
const auth = computed(() => page.props.auth);

const isCurrentRoute = computed(() => (url: string) => page.url === url);

const activeItemStyles = computed(
    () => (url: string) => (isCurrentRoute.value(url) ? 'text-neutral-900 dark:bg-neutral-800 dark:text-neutral-100' : ''),
);

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
];

const rightNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <div>
        <div class="border-b border-sidebar-border/80">
            <div class="mx-auto flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                <!-- Mobile Menu Button -->
                <div class="flex items-center sm:hidden">
                    <Sheet>
                        <SheetTrigger :as-child="true">
                            <Button variant="ghost" size="icon" class="h-9 w-9">
                                <Menu class="h-5 w-5" />
                                <span class="sr-only">Menu</span>
                            </Button>
                        </SheetTrigger>
                        <SheetContent side="left" class="flex w-[280px] flex-col p-0">
                            <div class="border-b border-border px-6 py-4">
                                <Link :href="route('dashboard')" class="flex items-center gap-x-2">
                                <AppLogo />
                                </Link>
                            </div>
                            <div class="flex-1 overflow-y-auto px-6 py-4">
                                <nav class="flex flex-col space-y-1">
                                    <Link v-for="item in mainNavItems" :key="item.title" :href="item.href"
                                        class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors hover:bg-accent"
                                        :class="[
                                            activeItemStyles(item.href),
                                            'hover:text-neutral-900 dark:hover:text-neutral-100'
                                        ]">
                                    <component v-if="item.icon" :is="item.icon" class="h-5 w-5" />
                                    {{ item.title }}
                                    </Link>
                                </nav>
                            </div>
                            <div class="border-t border-border px-6 py-4">
                                <div class="flex flex-col space-y-4">
                                    <div class="flex items-center gap-2">
                                        <Button variant="ghost" size="icon" class="group h-9 w-9">
                                            <Search class="size-5 opacity-80 group-hover:opacity-100" />
                                        </Button>
                                        <template v-for="item in rightNavItems" :key="item.title">
                                            <Button variant="ghost" size="icon" as-child class="group h-9 w-9">
                                                <a :href="item.href" target="_blank" rel="noopener noreferrer"
                                                    class="flex items-center gap-2">
                                                    <component :is="item.icon"
                                                        class="size-5 opacity-80 group-hover:opacity-100" />
                                                    <span class="sr-only">{{ item.title }}</span>
                                                </a>
                                            </Button>
                                        </template>
                                    </div>
                                    <DropdownMenu>
                                        <DropdownMenuTrigger :as-child="true">
                                            <Button variant="ghost" class="w-full justify-start gap-2 px-2">
                                                <Avatar class="size-8">
                                                    <AvatarImage v-if="auth.user.avatar" :src="auth.user.avatar"
                                                        :alt="auth.user.name" />
                                                    <AvatarFallback
                                                        class="bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                                        {{ getInitials(auth.user?.name) }}
                                                    </AvatarFallback>
                                                </Avatar>
                                                <div class="flex flex-col items-start text-sm">
                                                    <span class="font-medium">{{ auth.user.name }}</span>
                                                    <span class="text-xs text-muted-foreground">{{ auth.user.email
                                                        }}</span>
                                                </div>
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end" class="w-56">
                                            <UserMenuContent :user="auth.user" />
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </div>
                            </div>
                        </SheetContent>
                    </Sheet>
                </div>

                <!-- Logo -->
                <div class="flex flex-1 items-center sm:flex-initial">
                    <Link :href="route('dashboard')" class="flex items-center gap-x-2">
                    <AppLogo />
                    </Link>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <NavigationMenu class="ml-6">
                        <NavigationMenuList class="flex items-center space-x-2">
                            <NavigationMenuItem v-for="(item, index) in mainNavItems" :key="index" class="relative">
                                <Link :class="[
                                    navigationMenuTriggerStyle(),
                                    activeItemStyles(item.href),
                                    'h-9 cursor-pointer px-3'
                                ]" :href="item.href">
                                <component v-if="item.icon" :is="item.icon" class="mr-2 h-4 w-4" />
                                {{ item.title }}
                                </Link>
                                <div v-if="isCurrentRoute(item.href)"
                                    class="absolute bottom-0 left-0 h-0.5 w-full translate-y-px bg-black dark:bg-white">
                                </div>
                            </NavigationMenuItem>
                        </NavigationMenuList>
                    </NavigationMenu>

                    <!-- Desktop Right Side -->
                    <div class="flex items-center gap-2">
                        <Button variant="ghost" size="icon" class="group h-9 w-9">
                            <Search class="size-5 opacity-80 group-hover:opacity-100" />
                        </Button>

                        <div class="flex items-center gap-1">
                            <template v-for="item in rightNavItems" :key="item.title">
                                <TooltipProvider :delay-duration="0">
                                    <Tooltip>
                                        <TooltipTrigger>
                                            <Button variant="ghost" size="icon" as-child class="group h-9 w-9">
                                                <a :href="item.href" target="_blank" rel="noopener noreferrer">
                                                    <span class="sr-only">{{ item.title }}</span>
                                                    <component :is="item.icon"
                                                        class="size-5 opacity-80 group-hover:opacity-100" />
                                                </a>
                                            </Button>
                                        </TooltipTrigger>
                                        <TooltipContent>
                                            <p>{{ item.title }}</p>
                                        </TooltipContent>
                                    </Tooltip>
                                </TooltipProvider>
                            </template>
                        </div>

                        <DropdownMenu>
                            <DropdownMenuTrigger :as-child="true">
                                <Button variant="ghost" size="icon" class="relative size-10 w-auto rounded-full p-1">
                                    <Avatar class="size-8">
                                        <AvatarImage v-if="auth.user.avatar" :src="auth.user.avatar"
                                            :alt="auth.user.name" />
                                        <AvatarFallback
                                            class="bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                            {{ getInitials(auth.user?.name) }}
                                        </AvatarFallback>
                                    </Avatar>
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" class="w-56">
                                <UserMenuContent :user="auth.user" />
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breadcrumbs -->
        <div v-if="props.breadcrumbs.length > 1" class="border-b border-sidebar-border/70">
            <div class="mx-auto flex h-12 w-full items-center justify-start px-4 text-neutral-500 sm:px-6 lg:px-8">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </div>
        </div>
    </div>
</template>
