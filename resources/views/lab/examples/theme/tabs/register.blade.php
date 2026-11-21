<div class="mx-auto flex w-full max-w-md flex-col py-2">
    <flux:card class="flex flex-col gap-5 p-6">
        <div class="flex flex-col gap-1 text-center">
            <flux:heading size="lg">Create your account</flux:heading>
            <flux:subheading>Start building with your custom design tokens.</flux:subheading>
        </div>

        <div class="grid grid-cols-2 gap-2.5">
            <flux:button type="button" variant="outline" class="w-full">
                <svg class="me-1.5 size-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" />
                </svg>
                GitHub
            </flux:button>

            <flux:button type="button" variant="outline" class="w-full">
                <svg class="me-1.5 size-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z" />
                </svg>
                Google
            </flux:button>
        </div>

        <flux:separator text="or continue with email" />

        <form x-on:submit.prevent class="flex flex-col gap-4">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <flux:field>
                    <flux:label>First name</flux:label>
                    <flux:input value="Alex" placeholder="Alex" />
                </flux:field>

                <flux:field>
                    <flux:label>Last name</flux:label>
                    <flux:input value="Morgan" placeholder="Morgan" />
                </flux:field>
            </div>

            <flux:field>
                <flux:label>Work email</flux:label>
                <flux:input type="email" icon="envelope" value="alex@acme.inc" placeholder="you@company.com" />
            </flux:field>

            <flux:field>
                <flux:label>Team role</flux:label>
                <flux:select>
                    <flux:select.option selected>Product Designer</flux:select.option>
                    <flux:select.option>Frontend Engineer</flux:select.option>
                    <flux:select.option>Design Systems Lead</flux:select.option>
                </flux:select>
            </flux:field>

            <flux:field>
                <flux:label>Password</flux:label>
                <flux:input type="password" value="supersecret123" viewable />
                <flux:description>Must be at least 8 characters with one symbol.</flux:description>
            </flux:field>

            <flux:checkbox checked label="I agree to the Terms of Service and Privacy Policy" />

            <flux:button type="submit" variant="primary" class="w-full">
                Create account
            </flux:button>
        </form>

        <flux:text size="xs" class="text-center text-zinc-500">
            Already have an account?
            <button type="button" class="font-medium text-(--color-accent-content) hover:underline">
                Sign in
            </button>
        </flux:text>
    </flux:card>
</div>
