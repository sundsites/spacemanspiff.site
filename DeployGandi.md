# Deploy

# add the gandi repo

```bash
git remote add gandi git+ssh://2369634@git.dc2.gpaas.net/spacemanspiff.site.git
```

# commit changes

```bash
git push
git push gandi main
```

# Deploy to gandi hosting

```bash
ssh 2369634@git.dc2.gpaas.net deploy spacemanspiff.site.git main
```