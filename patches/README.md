# Temporary development patches

## Scalar function defaults in Pest mutation coverage

`pest-mutate-function-defaults.patch` targets `pestphp/pest-plugin-mutate` v5.0.2, source revision `d0c87110416b699413cc073b8dcc92e440d81931`.

The stock tool expands a mutated parameter's covered range only when it belongs to a class method. A scalar default in a free function is restricted to its declaration line, so tests that execute the function body are never selected. The local reproduction reported four gallery defaults as uncovered; direct replays showed that existing tests catch two of the changed defaults.

The patch uses PHP-Parser's `FunctionLike` interface to select the complete body for methods, free functions, closures, and arrow functions. It does not address a scalar nested inside another expression. The guard exercises these four immediate scalar-default forms. Registration is root-only in `patches.json`, so this development patch does not propagate to consumers.

Implementation is tracked in UAMS-Web/uams-2020#641. Follow-ups are UAMS-Web/uams-2020#671, “Report mutation coverage for function defaults upstream”; UAMS-Web/uams-2020#672, “Offer the function-default mutation range fix upstream”; and UAMS-Web/uams-2020#673, “Retire the function-default mutation patch after a fixed release.” Their dependencies are recorded natively in GitHub.

### Verification

- `tests/Unit/MutationToolPatchTest.php` fails on the stock package because a declaration-only range does not reach the function body, and passes after the patch for all four function forms.
- The added signature `instanceof Node\FunctionLike` occurs once in the patched file and is absent from the stock revision.
- `php tests/mutate.php` remains the full regression gate; a passing presence guard alone is insufficient.

### Retirement

Retire this patch only after a compatible published tag contains the correction and the unpatched tool passes the same mutation regression gate.

1. Remove the registration before updating the package.
2. Retire the patch file and refresh `patches.lock.json`.
3. Retire the temporary guard and its `tests/Fixtures/mutation-function-defaults.php` fixture.
4. Update this narrative and retain an annotated decision history.
5. If no patches remain, retire the patch helper, its plugin permission, and the root patch files; refresh the Composer lock when its manifest changes.
6. Verify a fresh install, Composer validation, and the full eight-file mutation run.

No patch-specific PHPStan exception is present: the repository excludes tests from that analysis. No upstream watcher workflow is added here.
