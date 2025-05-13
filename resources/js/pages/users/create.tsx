import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useTranslation } from '@/hooks/use-translation';
import AppLayout from '@/layouts/app-layout';
import { cn } from '@/lib/utils';
import { BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/react';
import React, { useMemo } from 'react';
import { toast } from 'react-hot-toast';

interface Props {
    roles: {
        id: number;
        name: string;
    }[];
}

interface FormData {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    roles: string[];
    [key: string]: string | string[];
}

export default function Create({ roles }: Props) {
    const { __ } = useTranslation();
    const { data, setData, post, processing, errors } = useForm<FormData>({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        roles: [],
    });

    const breadcrumbs = useMemo<BreadcrumbItem[]>(
        () => [
            {
                title: __('Users'),
                href: '/users',
            },
            {
                title: __('Create User'),
                href: '/users/create',
            },
        ],
        [__],
    );

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();

        post('/users', {
            onSuccess: () => {
                toast.success(__('User created successfully'));
                router.visit('/users');
            },
            onError: (errors) => {
                Object.values(errors).forEach((error) => {
                    toast.error(error);
                });
            },
        });
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={__('Create User')} />
            <div className="flex h-full flex-1 flex-col gap-6 p-6">
                {' '}
                {/* Increased padding and gap */}
                <Card>
                    <CardHeader>
                        <CardTitle>{__('Create User')}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handleSubmit} className="space-y-6">
                            {' '}
                            {/* Increased space between form elements */}
                            <div className="space-y-2">
                                <Label htmlFor="name">{__('Name')}</Label>
                                <Input
                                    id="name"
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    className={cn(errors.name && 'border-destructive')}
                                />
                                {errors.name && <p className="text-destructive text-sm">{errors.name}</p>}
                            </div>
                            <div className="space-y-2">
                                <Label htmlFor="email">{__('Email')}</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    className={cn(errors.email && 'border-destructive')}
                                />
                                {errors.email && <p className="text-destructive text-sm">{errors.email}</p>}
                            </div>
                            <div className="space-y-2">
                                <Label htmlFor="password">{__('Password')}</Label>
                                <Input
                                    id="password"
                                    type="password"
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    className={cn(errors.password && 'border-destructive')}
                                />
                                {errors.password && <p className="text-destructive text-sm">{errors.password}</p>}
                            </div>
                            <div className="space-y-2">
                                <Label htmlFor="password_confirmation">{__('Confirm Password')}</Label>
                                <Input
                                    id="password_confirmation"
                                    type="password"
                                    value={data.password_confirmation}
                                    onChange={(e) => setData('password_confirmation', e.target.value)}
                                    className={cn(errors.password_confirmation && 'border-destructive')}
                                />
                                {errors.password_confirmation && <p className="text-destructive text-sm">{errors.password_confirmation}</p>}
                            </div>
                            <div className="space-y-2">
                                <Label>{__('Assign Role')}</Label>
                                <Select value={data.roles[0] || ''} onValueChange={(value) => setData('roles', [value])}>
                                    <SelectTrigger>
                                        <SelectValue placeholder={__('Select a role')} />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {roles?.map((role) => (
                                            <SelectItem key={role.id} value={role.name}>
                                                {role.name}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {errors.roles && <p className="text-destructive text-sm">{errors.roles}</p>}
                            </div>
                            <div className="flex justify-end gap-4">
                                <Button type="button" variant="outline" onClick={() => router.get('/users')}>
                                    {__('Cancel')}
                                </Button>
                                <Button type="submit" disabled={processing}>
                                    {processing ? __('Creating...') : __('Create User')}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
