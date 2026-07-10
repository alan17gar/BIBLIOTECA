import subprocess
import os

def check_valid_fpdf(rev):
    try:
        # Check if the file exists in this revision
        files = subprocess.check_output(['git', 'ls-tree', '-r', rev], stderr=subprocess.DEVNULL).decode()
        if 'libs/fpdf/fpdf.php' not in files:
            return False

        # Get the first 500 bytes of the file
        content = subprocess.check_output(['git', 'show', f'{rev}:libs/fpdf/fpdf.php'], stderr=subprocess.DEVNULL)
        if b'class FPDF' in content:
            return True
    except:
        pass
    return False

# Get all commit hashes
all_revs = subprocess.check_output(['git', 'rev-list', '--all']).decode().split()

print(f"Checking {len(all_revs)} revisions...")
for rev in all_revs:
    if check_valid_fpdf(rev):
        print(f"FOUND: {rev}")
        # Restore the library from this revision
        subprocess.run(['git', 'checkout', rev, '--', 'libs/fpdf'])
        exit(0)

print("Not found in any revision.")
exit(1)
