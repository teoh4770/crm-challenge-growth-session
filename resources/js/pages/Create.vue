<template>
    <Form action="/clients" method="post">
        <div>
            <label for="name">Name</label>
            <input type="text" name="name" />
        </div>

        <div>
            <label for="email">Email</label>
            <input type="email" name="email" />
        </div>

        <div>
            <label for="phone">Phone</label>
            <input type="tel" name="phone" />
        </div>

        <div>
            <label for="company">Company</label>
            <input type="text" name="company" />
        </div>

        <div>
            <label for="address">Address</label>
            <input type="text" name="address" />
        </div>

        <button type="submit">Create User</button>
    </Form>
</template>

<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
</script>

'name' => ['required'], 'email' => ['required', 'email', 'max:254',
'unique:clients,email'], 'phone' => ['required'], 'company' => ['required'],
'address' => ['required'], 'status' => ['required',
Rule::enum(StatusEnum::class)],
